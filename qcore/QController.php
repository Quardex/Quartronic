<?php
namespace quarsintex\quartronic\qcore;

class QController extends QSource
{
    protected $action;

    protected function getConnectedProperties()
    {
        return [
            'requireAuth' => &self::$Q->params['requireAuth'],
            'currentUser' => &self::$Q->user,
            'render' => &self::$Q->render,
        ];
    }

    function __construct($action)
    {
        if (is_array($action)) {
            $action = $action[1];
        }
        $action = strtolower($action);
        if ($this->requireAuth && $action != 'signin') {
            $qtoken = isset(self::$Q->request->cookie['qtoken']) ? self::$Q->request->cookie['qtoken'] : '';
            $needAuth = true;
            if ($qtoken) {
                list($username, $userhash) = explode('|', base64_decode($qtoken));
                $user = \quarsintex\quartronic\qmodels\QUser::findOne(['where'=>['username'=>$username]]);
                if ($user->passhash == $userhash) {
                    self::$Q->defineUser($user);
                    $needAuth = false;
                }
            }
            if ($needAuth) $this->redirect('/user/signin');
        }
        $this->action = $action;
    }

    function redirect($target, $raw = false)
    {
        if (!$raw) $target = self::$Q->urlManager->route($target);
        header('Location: '.$target, true, 302);
        exit;
    }
}

?>