<?php

namespace backend\util;

class Permissions
{
    const ROLE_INDEX = 'role index';
    const ROLE_UPSERT = 'role upsert';
    const ROLE_DELETE = 'role delete';
    const ROLE_ACCESS = 'role access';

    const SETTING_MENU_LIST = 'setting list';
    const SETTING_GENERAL = 'setting general';
    const SETTING_EMAIL = 'setting email';
    const SETTING_PAYPAL = 'setting paypal';
    const SETTING_STRIPE = 'setting stripe';

    const USER_INDEX = 'user index';
    const USER_UPSERT = 'user upsert';
    const USER_DELETE = 'user delete';
}