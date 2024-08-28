<?php

namespace App\Livewire;

use App\Models\AreaOfInterest;
use App\Models\CaseStudy;
use App\Models\Chapter;
use App\Models\Newspaper;
use App\Models\Topic;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @method LengthAwarePaginator pageTracker
 */
class Table extends Component
{
    use WithPagination;

    public function getPageTrackerProperty(): LengthAwarePaginator
    {
        return AreaOfInterest::query()
            ->select([
                DB::raw('COALESCE(ranked_areas_of_interest.area_of_interest_count, 0) as area_of_interest_count'),
                'area_of_interests.id',
                'area_of_interests.name',
                'users.name as most_active_user'
            ])
            ->leftJoinSub($this->mostViewedAreasOfInterestSubQuery(), 'ranked_areas_of_interest', function (JoinClause $join) {
                $join->on('area_of_interests.id', '=', 'ranked_areas_of_interest.area_of_interest_id');
            })
            ->leftJoinSub($this->mostActiveUserByAreaOfInterestSubQuery(), 'ranked_users', function (JoinClause $join) {
                $join->on('area_of_interests.id', '=', 'ranked_users.area_of_interest_id');
            })
            ->leftJoin('users', 'ranked_users.user_id', '=', 'users.id')
            ->orderBy('ranked_areas_of_interest.area_of_interest_count', 'desc')
            ->paginate(10);
    }

    private function subqueryMapping(string $trackableType): array
    {
        return match($trackableType) {
            Newspaper::class => ['table' => 'area_of_interest_newspaper', 'id_column' => 'area_of_interest_newspaper.newspaper_id'],
            Topic::class     => ['table' => 'area_of_interest_topic', 'id_column' => 'area_of_interest_topic.topic_id'],
            Chapter::class   => ['table' => 'area_of_interest_chapter', 'id_column' => 'area_of_interest_chapter.chapter_id'],
            CaseStudy::class => ['table' => 'area_of_interest_case_study', 'id_column' => 'area_of_interest_case_study.case_study_id'],
        };
    }

    private function areasOfInterestSubQueryBuilder(string $trackableType): Builder
    {
        $mapping = $this->subqueryMapping($trackableType);

        return DB::table('page_trackers')
            ->join($mapping['table'], 'page_trackers.trackable_id', '=', $mapping['id_column'])
            ->where('trackable_type', $trackableType)
            ->select([
                DB::raw('COUNT(*) as area_of_interest_count'),
                $mapping['table'].".area_of_interest_id"
            ])
            ->groupBy($mapping['table'].".area_of_interest_id");
    }

    private function mostActiveUserSubQueryBuilder(string $trackableType): Builder
    {
        $mapping = $this->subqueryMapping($trackableType);

        return DB::table('page_trackers')
            ->join($mapping['table'], 'page_trackers.trackable_id', '=', $mapping['id_column'])
            ->where('trackable_type', $trackableType)
            ->select([
                'page_trackers.user_id',
                $mapping['table'].".area_of_interest_id",
            ])
            ->groupBy('page_trackers.user_id', $mapping['table'].".area_of_interest_id");
    }

    private function mostActiveUserByAreaOfInterestSubQuery(): Builder
    {
        $rankedNewspaper   = $this->mostActiveUserSubQueryBuilder(Newspaper::class);
        $rankedTopics      = $this->mostActiveUserSubQueryBuilder(Topic::class);
        $rankedChapters    = $this->mostActiveUserSubQueryBuilder(Chapter::class);
        $rankedCaseStudies = $this->mostActiveUserSubQueryBuilder(CaseStudy::class);

        $combinedData = DB::query()
            ->fromSub($rankedNewspaper->unionAll($rankedTopics)
                ->unionAll($rankedChapters)
                ->unionAll($rankedCaseStudies), 'combined_data')
            ->select([
                'area_of_interest_id',
                'user_id',
                DB::raw('COUNT(*) as area_of_interest_count'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY area_of_interest_id ORDER BY COUNT(*) DESC) as rn')
            ])
            ->groupBy('area_of_interest_id', 'user_id');

        return DB::query()
            ->fromSub($combinedData, 'ranked_data')
            ->select([
                DB::raw('MAX(area_of_interest_count) as total_count'),
                'area_of_interest_id',
                'user_id'
            ])
            ->where('rn', 1)
            ->groupBy('area_of_interest_id', 'user_id');
    }

    private function mostViewedAreasOfInterestSubQuery(): Builder
    {
        $rankedNewspaper   = $this->areasOfInterestSubQueryBuilder(Newspaper::class);
        $rankedTopics      = $this->areasOfInterestSubQueryBuilder(Topic::class);
        $rankedChapters    = $this->areasOfInterestSubQueryBuilder(Chapter::class);
        $rankedCaseStudies = $this->areasOfInterestSubQueryBuilder(CaseStudy::class);

        return DB::query()
            ->fromSub($rankedNewspaper->unionAll($rankedTopics)
                ->unionAll($rankedChapters)
                ->unionAll($rankedCaseStudies), 'combined_data')
            ->select([
                DB::raw('SUM(area_of_interest_count) as area_of_interest_count'),
                'area_of_interest_id'
            ])
            ->groupBy('area_of_interest_id');
    }

    function secondsForHumans(string|int|null $seconds,): string
    {
        if (blank($seconds)) {
            return '0s';
        }

        return rescue(
            callback: function () use ($seconds) {
                return CarbonInterval::seconds((int) $seconds ?? 0)->cascade()->forHumans(short: true);
            },
            rescue: fn () => '0s',
            report: false
        );
    }

    public function render(): View
    {
        return view('livewire.table');
    }
}
