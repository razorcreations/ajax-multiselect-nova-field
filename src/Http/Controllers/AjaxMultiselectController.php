<?php

namespace Bessamu\AjaxMultiselectNovaField\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class AjaxMultiselectController extends Controller
{
    private const DEFAULT_LIMIT = 20;

    public function getOptionsByQuery(NovaRequest $request)
    {
        $query = $request->input('query');
        $custom_query = $request->input('custom_query');
        $label = $request->input('label');
        /** @var Builder $builder */
        $builder = ($request->input('class'))::query();

        $builder->where($label, 'like', "%$query%");
        if ($custom_query) {
            $col = strtok($custom_query, '?');
            $value = str_replace('=', '', strtok( '' ));
            $builder->whereRaw($col . '?', $value);
        }
        $builder->limit($request->input('limit') ?? self::DEFAULT_LIMIT);

        return response()->json($builder->get());
    }

    public function getOptions(NovaRequest $request)
    {
        $items = ($request->input('class'))::findOrFail(json_decode($request->input('value')));

        return response()->json($items);
    }
}
