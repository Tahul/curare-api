<?php

namespace App\Http\Controllers\OpenGraph;

use App\Helpers\OpenGraph\Parser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\LinkPreviewRequest;
use Exception;
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
        $data = [];
        $domain = parse_url($request->url)["host"];

        try {
            $content = file_get_contents($request->url);

            $data = Parser::parse($content);
        } catch (Exception $e) {
            // Neglect this case, wrong URL.
            info($e);
        }

        return response()->json(
            array_merge(
                [
                    'domain' => $domain,
                    'favicon' => 'https://www.google.com/s2/favicons?domain=' . $domain
                ],
                $data
            )
        );
    }
}
