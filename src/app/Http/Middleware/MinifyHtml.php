<?php

namespace JamesGordo\LaravelMinifyHtml\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // get the response content type
        $contentType = strtolower(strtok($response->headers->get('Content-Type'), ';'));

        if ($response instanceof Response && $contentType === 'text/html') {
            /**
             * Regular Expressions derived from this solution
             *
             * @see https://stackoverflow.com/a/48123642
             */
            $expressions = [
                '/(\n|^)(\x20+|\t)/'        => "\n",
                '/(\n|^)\/\/(.*?)(\n|$)/'   => "\n",
                '/\n/'                      => " ",
                '/\<\!--.*?-->/'            => "",
                '/(\x20+|\t)/'              => " ",     # Delete multispace (Without \n)
                '/\>\s+\</'                 => "><",    # strip whitespaces between tags
                '/(\"|\')\s+\>/'            => "$1>",   # strip whitespaces between quotation ("') and end tags
                '/=\s+(\"|\')/'             => "=$1",   # strip whitespaces between = "',
            ];

            // minify content
            $response->setContent(
                preg_replace(
                    array_keys($expressions),
                    array_values($expressions),
                    $response->getContent()
                )
            );
        }

        return $response;
    }
}
