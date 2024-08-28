<?php

namespace App\Livewire;

use App\Models\CaseStudy;
use App\Models\Chapter;
use App\Models\Newspaper;
use App\Models\PageTracker;
use App\Models\Topic;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
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
        return PageTracker::query()
            ->select([
                $this->calculateTotalTimeOnPlatform(),
                'users.name as user_name',
                'area_of_interests.name as area_of_interest_name',
            ])
            ->leftJoinSub($this->subQueryMostViewedAreasOfInterest(), 'ranked_aoi', function (JoinClause $join) {
                $join->on('page_trackers.user_id', '=', 'ranked_aoi.user_id')
                    ->where('ranked_aoi.rn_area_of_interest', '=', 1);
            })
            ->leftJoin('area_of_interests', 'ranked_aoi.area_of_interest_id', '=', 'area_of_interests.id')
            ->leftJoin('users', 'page_trackers.user_id', '=', 'users.id')
            ->groupBy(
               'time_on_platform',
                'page_trackers.user_id',
                'area_of_interests.id',
            )
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

    private function calculateTotalTimeOnPlatform(): Expression
    {
        $timeSpent = PageTracker::query()
            ->selectRaw('SUM(time_spent)')
            ->whereColumn('user_id', 'users.id')
            ->toRawSql();

        return DB::raw("($timeSpent) as time_on_platform");
    }

    private function rankedAreaOfInterestSubQueryBuilder(string $trackableType): Builder
    {
        $mapping = $this->subqueryMapping($trackableType);

        return DB::table('page_trackers')
            ->join($mapping['table'], 'page_trackers.trackable_id', '=', $mapping['id_column'])
            ->where('trackable_type', $trackableType)
            ->select([
                $mapping['table'].".area_of_interest_id",
                'page_trackers.user_id'
            ])
            ->groupBy($mapping['table'].".area_of_interest_id", 'page_trackers.user_id');
    }

    private function subQueryMostViewedAreasOfInterest(): Builder
    {
        $rankedNewspaper = $this->rankedAreaOfInterestSubQueryBuilder(Newspaper::class);
        $rankedTopics    = $this->rankedAreaOfInterestSubQueryBuilder(Topic::class);
        $rankedChapters  = $this->rankedAreaOfInterestSubQueryBuilder(Chapter::class);
        $rankedCaseStudy = $this->rankedAreaOfInterestSubQueryBuilder(CaseStudy::class);

//        $combinedData = DB::table(DB::raw("({$rankedNewspaper->toRawSql()} UNION ALL {$rankedTopics->toRawSql()}) as combined_data"))
//            ->select('user_id', 'area_of_interest_id');
//
//        return DB::table(DB::raw("({$rankedChapters->toRawSql()} UNION ALL {$combinedData->toRawSql()}) as ranked_diseases"))
//            ->select(
//                'user_id',
//                'area_of_interest_id',
//                DB::raw('COUNT(*) as area_of_interest_count'),
//                DB::raw('ROW_NUMBER() OVER(PARTITION BY user_id ORDER BY COUNT(*) DESC) as rn_area_of_interest')
//            )
//            ->groupBy('user_id', 'area_of_interest_id');

        return DB::query()
            ->fromSub($rankedNewspaper->unionAll($rankedTopics)
                ->unionAll($rankedChapters)
                ->unionAll($rankedCaseStudy), 'combined_data')
            ->select([
                'user_id',
                'area_of_interest_id',
                DB::raw('COUNT(*) as area_of_interest_count'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY COUNT(*) DESC) as rn_area_of_interest')
            ])
            ->groupBy('user_id', 'area_of_interest_id');
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
