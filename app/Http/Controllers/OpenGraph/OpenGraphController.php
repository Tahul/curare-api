<?php

namespace App\Http\Controllers\OpenGraph;

use App\Http\Controllers\Controller;
use App\Http\Requests\Link\LinkPreviewRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use ogp\Parser;

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
        $data = null;

        try {
            $content = file_get_contents($request->url);

            $data = Parser::parse($content);
        } catch (Exception $e) {
            // Neglect this case, wrong URL.
        }

        return response()->json(
            $data
        );
    }
}
