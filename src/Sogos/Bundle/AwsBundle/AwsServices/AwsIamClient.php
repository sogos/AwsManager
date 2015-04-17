<?php


namespace Sogos\Bundle\AwsBundle\AwsServices;

use Aws\Iam\IamClient;


class AwsIamClient {

    protected $iamClient;


    public function __construct() {

        $this->iamClient = IamClient::factory(array(
            'profile' => 'default',
            'region'  => 'eu-west-1'
        ));

    }

    /**
     * @return IamClient
     */
    public function getIamClient()
    {
        return $this->iamClient;
    }

    /**
     * @return mixed|null
     */
    public function getUser()
    {
        $user_data =  $this->iamClient->getUser();
        $user = $user_data->get('User');
        return $user;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        $user = $this->getUser();
        $arn_user = $user['Arn'];
        preg_match("/arn\:aws\:iam\:\:(.*)\:user.*/", $arn_user, $match);
        $account_id = $match[1];
        return $account_id;
    }
}