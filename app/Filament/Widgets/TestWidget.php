<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{

    use InteractsWithPageFilters;

    protected function getStats(): array
    {

        $start = $this->filters['startDate'] ?? null;
        $end = $this->filters['endDate'] ?? null;


      //  $start = $this->filters['startDate'];
      //  $end = $this->filters['endDate'];


        return [
            //
            Stat::make('New Users', 
            User::
            when($start,
            fn ($query) => $query->whereDate('created_at', '>',$start))

           ->when($end,
            fn ($query) => $query->whereDate('created_at', '<',$end))

            ->count()
            )
            ->description('New users that have joined')
            ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
            ->chart([1,3,5,10,20,40])
            ->color('success')

            
,
Stat::make('New Comments', Comment::count())
->description('Comments')
->descriptionIcon('heroicon-s-chat-bubble-bottom-center-text', IconPosition::Before)
->chart([1,3,5,10,20,40])
->color('danger')

,
Stat::make('New Post', Post::count())
->description('Posts')
->descriptionIcon('heroicon-o-document-text', IconPosition::Before)
->chart([1,3,5,10,20,40])
->color('gray')
,
Stat::make('New Category', Category::count())
->description('Category')
->descriptionIcon('heroicon-o-folder', IconPosition::Before)
->chart([1,3,5,10,20,40])
->color('primary')


        ];
    }
}
