<?php

namespace App\Http\Controllers\OpenGraph;

use App\Helpers\OpenGraph\Parser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\LinkPreviewRequest;
use Illuminate\Http\JsonResponse;

class OpenGraphController extends Controller
{
    /**
     * Return the OpenGraph parsed data for a specified link.
     *
     * @param LinkPreviewRequest $request
     * @return JsonResponse
     */
    public function preview(LinkPreviewRequest $request)
    {
        return response()->json(
            Parser::parse($request->url)
        );
    }
}
