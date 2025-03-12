@php

    $categories = App\Models\Category::whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query -> with('children');
                }])
                ->get();

    dd($categories);




@endphp