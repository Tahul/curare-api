<?php

namespace App\Observers;

use App\Models\Collection\Collection;
use Illuminate\Support\Str;

class CollectionObserver
{
    /**
     * Handle the collection "saving" event.
     *
     * @param Collection $collection
     * @return void
     */
    public function saving(Collection $collection)
    {
        // Generate new slug using the Laravel helper
        $collection->slug = Str::slug($collection->title, "-");

        // Creating a column with a column_id & an order
        if (!$collection->order) {
            // Getting all the columns with the same board_id
            $collections = Collection::where('user_id', $collection->user_id)->orderBy('order', 'asc')->get();

            // Setting column order
            $collection->order = $collections->count() + 1;
        }
    }
}
