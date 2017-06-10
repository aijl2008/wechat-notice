<?php
namespace Awz\Notice\Http\Controllers;

use App\Models\Notice;
use Awz\Notice\Jobs\SendNotice;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class NoticeController extends Controller {

    protected $config = [ ];
    protected $app    = null;

    function __construct () {
        $this->config = [
            'debug' => config ( 'notice.debug' ) ,
            'app_id' => config ( 'notice.app_id' ) ,
            'secret' => config ( 'notice.secret' ) ,
            'token' => config ( 'notice.token' ) ,
            'aes_key' => config ( 'notice.aes_key' ) ,
            'log' => config ( 'notice.log' )
        ];
    }

    public function api ( Request $request ) {
        $message = $request->input ( 'message' );
        if ( !$message ) {
            return response ()->json ( [
                'code' => 1 ,
                'count' => '消息不能为空'
            ] );
        }
        $url = $request->input ( 'url' );
        $openid = $request->input ( 'openid' );
        $this->app = new Application( $this->config );
        if ( $openid ) {
            return $this->sendNoticeTo ( $openid , $message , $url );
        }
        return $this->sendNotice ( $message , $url );
    }

    protected function sendNoticeTo ( $openids , $message , $url ) {
        $notice = [
            'template_id' => config ( 'notice.template_id' ) ,
            'data' => array (
                "msg" => $message ,
            ) ,
        ];
        if ( $url ) {
            $notice[ 'url' ] = $url;
        }
        $count = 0;
        foreach ( (array) $openids as $openid ) {
            $notice[ 'touser' ] = $openid;
            dispatch ( ( new SendNotice( $this->app->notice , $notice ) ) );
            $count++;
        }
        return response ()->json ( [
            'code' => 0 ,
            'count' => $count
        ] );
    }

    protected function sendNotice ( $message , $url ) {
        $notice = [
            'template_id' => config ( 'notice.template_id' ) ,
            'data' => array (
                "msg" => $message ,
            ) ,
        ];
        if ( $url ) {
            $notice[ 'url' ] = $url;
        }
        $nextOpenId = null;
        $count = 0;
        while ( true ) {
            $users = $this->app->user->lists ( $nextOpenId )->all ();
            if ( !$users[ 'next_openid' ] ) {
                break;
            }
            $nextOpenId = $users[ 'next_openid' ];
            foreach ( $users[ 'data' ][ 'openid' ] as $openid ) {
                $notice[ 'touser' ] = $openid;
                dispatch ( ( new SendNotice( $this->app->notice , $notice ) ) );
                $count++;
            }
        }
        return response ()->json ( [
            'code' => 0 ,
            'count' => $count
        ] );
    }
}
