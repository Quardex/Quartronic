<?php
namespace quarsintex\quartronic\qcontrollers;

class UserController extends \quarsintex\quartronic\qcore\QCrudController
{
    const MODEL = 'QUser';

    public function actSignIn()
    {
        $user = $this->crud->model;
        if (self::$Q->request->post && $user->authorize(self::$Q->request->post)) $this->redirect('/');
        return self::$Q->render->run('',['model'=>$user],'signin');
    }

    public function actSignOut()
    {
        setcookie('qtoken', '', 0, self::$Q->webPath);
        $this->redirect('/');
    }

    function actAdd($modelData = [])
    {
        $post = self::$Q->request->post;
        if ($post) {
            $post['passhash'] = password_hash($post['passhash'].$post['salt'], PASSWORD_DEFAULT);
        }
        return parent::actAdd($post);
    }
}

?>
