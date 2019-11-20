<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StyleController extends Controller
{
    /**
     * Serve the requested stylesheet.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(NovaRequest $request)
    {
        $path = Arr::get(Nova::allStyles(), $request->style);

        abort_if(is_null($path), 404);

        return BinaryFileResponse::create($path, 200, ['Content-Type' => 'text/css'], false)
                                 ->setAutoLastModified();
    }
}
