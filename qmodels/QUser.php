<?php
namespace quarsintex\quartronic\qmodels;

class QUser extends \quarsintex\quartronic\qcore\QModel
{
  protected function getConnectedProperties()
  {
    return [
        'db' => self::$Q->sysDB,
    ];
  }

  public function authorize($modelData)
  {
      $user = $this->findOne(['where'=>['username'=>$modelData['username']]]);
      if ($user && password_verify($modelData['password'], $user->passhash)) {
          setcookie('qtoken', base64_encode($user->username.'|'.$user->passhash), time()+3600*24*3, self::$Q->webPath);
      } else {
          $this->username = self::$Q->request->post['username'];
          $this->errors['username'] = 'Wrong username and password';
      }
      return !$this->errors;
  }
}

?>