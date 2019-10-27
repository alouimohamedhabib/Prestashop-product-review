<?php

class commentProductClass extends ObjectModelCore
{
    public $id_comment;
    public $user_id;
    public $product_id;
    public $comment;

    public static $definition = array(
        'table' => 'product_comment',
        'primary' => 'id_comment',
        'multilang' => true,
        'fields' => array(
            'id_comment' => array('type' => self::TYPE_INT, 'lang' => true, 'required' => true),
            'user_id' => array('type' => self::TYPE_INT, 'lang' => true, 'required' => true),
            'product_id' => array('type' => self::TYPE_INT, 'lang' => true, 'required' => true),
            'comment' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true),
        )
    );

}