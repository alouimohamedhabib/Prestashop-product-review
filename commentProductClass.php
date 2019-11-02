<?php

class commentProductClass extends ObjectModelCore
{
    public $id_comment;
    public $user_id;
    public $product_id;
    public $comment;
    public $active;

    public static $definition = array(
        'table' => 'product_comment',
        'primary' => 'id_comment',
        'multilang' => false,
        'fields' => array(
            'user_id' => array('type' => self::TYPE_INT, 'required' => true),
            'product_id' => array('type' => self::TYPE_INT, 'required' => true),
            'comment' => array('type' => self::TYPE_STRING, 'required' => true),
            'active' => array('type' => self::TYPE_BOOL, 'required' => true),         
        )
    );

}