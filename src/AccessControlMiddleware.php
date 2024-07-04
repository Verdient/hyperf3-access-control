<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 访问控制中间件
 * @author Verdient。
 */
class AccessControlMiddleware implements MiddlewareInterface
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dispatched = $request->getAttribute(Dispatched::class);
        if ($dispatched instanceof Dispatched && $dispatched->isFound()) {
            $credential = new Credential($request);
            switch ($credential->pass()) {
                case Result::PASS:
                    return $handler->handle($request->withAttribute(Credential::class, $credential));
                case Result::UNAUTHORIZED:
                    throw new HttpException(401, 'Unauthorized');
                case Result::FORBIDDEN:
                    throw new HttpException(403, 'Forbidden');
            }
        }
        return $handler->handle($request);
    }
}
