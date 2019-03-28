<?php
namespace SoerBot\WebServer;
    //composer require react/http:^0.8.4
    //composer require react/socket:^1.2
    use Psr\Http\Message\ServerRequestInterface;
    use React\EventLoop\Factory;
    use React\Http\Response;
    use React\Http\Server;
    use React\Socket\Server as Socket;

class HttpWebServer
{
    private $loop; 
    private $port;
    private $requestHandler;

    private $server;
    private $socket;

    /**
     * Readonly implements, thanks to https://ttmm.io/tech/php-read-attributes/
     * 
     * Magic getter for our object.
     *
     * @param string $field
     * @throws Exception Throws an exception if the field is invalid.
     * @return mixed
     */
    public function __get( $field ) {
        switch( $field ) {
        case 'port':
            return $this->port;
        case 'server':
            return $this->server;
        default:
            $class = __CLASS__;
            throw new Exception( "Invalid property: {$class}->{$field}");
        }
    }

    /**
     * Constrictor: 
     * - create instance of WebHookServer
     * 
     * @param $aLoop instance of React\EventLoop\LoopInterface
     * @param $aPort int 0..9999
     * @param $aRequestHandler instance of some like delegate function
     * @throws UnexpectedValueException
     */
    public function __construct($aLoop, $aPort, $aRequestHandler)
    {
        if (!($aLoop instanceof \React\EventLoop\LoopInterface))
            throw new UnexpectedValueException();
        if (!is_int($aPort)||($aPort<0||$aPort>9999))
            throw new UnexpectedValueException();
        // if (!($aRequestHandler instanceof \Psr\Http\Server\RequestHandlerInterface))
        // if (!($aRequestHandler instanceof \Psr\Http\Message\ServerRequestInterface))
        if (!\is_callable($aRequestHandler) && !\is_array($aRequestHandler))
            throw new UnexpectedValueException();

        $this->loop = $aLoop;
        $this->port = $aPort;
        $this->requestHandler = $aRequestHandler;

        // $this->client->emit('debug', "WebHookServer has created");
    }
    
    /**
     * StartServer: 
     * - starting server for listening spicified(in constructor) port.
     * - react on reguesrts with spicified(in constructor) reguesrts handler.
     */
    public function startServer(): HttpWebServer
    {
        // https://reactphp.org/
        // see example api: https://habr.com/ru/post/143317/
        // see using http sever: https://sergeyzhuk.me/2018/03/13/using-router-with-reactphp-http/
        $this->server = new Server($this->requestHandler);
        $this->socket = new Socket($this->port, $this->loop);
        $this->server->listen( $this->socket);

        // $this->client->emit('debug', "WebHookServer started(port{$this->port})");

        return $this;
    }
}
