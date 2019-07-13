<?php

namespace StaffAddon\XF\Pub\Controller;

use XF\Entity\User;
use XF\Entity\UserProfile;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    public function actionIndex(ParameterBag $params)
	   {
        $response = parent::actionIndex($params);
        $key = $this->filter('key', 'str');

        $staff = $this->finder('XF:User')->where('is_staff','1')->fetch();
        $allStaff = array(
          "Inhaber" => array(),
          "Serverleitung" => array(),
          "Administrator" => array(),
          "SrDeveloper" => array(),
          "Developer" => array(),
          "Test-Developer" => array(),
          "SrModerator" => array(),
          "Moderator" => array(),
          "Test-Moderator" => array(),
          "SrSupporter" => array(),
          "Supporter" => array(),
          "Test-Supporter" => array(),
          "SrBuilder" => array(),
          "Builder" => array(),
          "Test-Builder" => array(),
          "SrContent" => array(),
          "Content" => array(),
          "Test-Content" => array(),
        );

        $requiredUserGroups = array_keys($allStaff);

        foreach($staff as $member){
          $title = $this->getUserGroupNameById($member->user_group_id);
          if(in_array($title,$requiredUserGroups))
          {
              $allStaff[$title][] = $member;
          }

        }

        if($response instanceof \XF\Mvc\Reply\View)
        {
          $response->setParams([
            'allStaff' => $allStaff,
            'key' => $key
          ]);
        }


        return $response;
    }

    protected function getUserGroupNameById($id)
    {
        return $this->finder('XF:UserGroup')->where('user_group_id',$id)->fetchOne()->title;
    }
}
