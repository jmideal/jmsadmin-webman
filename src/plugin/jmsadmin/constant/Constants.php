<?php

namespace plugin\jmsadmin\constant;

class Constants
{
    /*=====缓存=====*/
    /**
     * 登录用户 redis key
     */
    const LOGIN_TOKEN_KEY = "jmsadmin_login_tokens:";


    /**
     * 登录账户密码错误次数 redis key
     */
    const USER_PWD_ERR_KEY = "jmsadmin_pwd_err_cnt:";

    /**
     * 验证码 redis key
     */
    const CAPTCHA_CODE_KEY = "jmsadmin_captcha_codes:";

    /**
     * 数据表结构信息 redis key
     */
    const TABLE_INFO_KEY = "jmsadmin_table_info:";

    /**
     * 参数管理 cache key
     */
    const CONFIG_KEY = "jmsadmin_config:";

    /**
     * 字典管理 cache key
     */
    const DICT_KEY = "jmsadmin_dict:";

    /*=====系统=====*/
    /** 校验是否唯一的返回标识 */
    const UNIQUE = true;

    const NOT_UNIQUE = false;

    /**
     * www主域
     */
    const WWW = "www.";
    /**
     * http请求
     */
    const HTTP = "http://";

    /**
     * https请求
     */
    const HTTPS = "https://";

    /** Layout组件标识 */
    const LAYOUT = "Layout";

    /** ParentView组件标识 */
    const PARENT_VIEW = "ParentView";

    /** InnerLink组件标识 */
    const INNER_LINK = "InnerLink";


    /*=====菜单=====*/
    /** 是否菜单外链（是） */
    const YES_FRAME = "1";

    /** 是否菜单外链（否） */
    const NO_FRAME = "0";

    /** 菜单类型（目录） */
    const TYPE_DIR = "M";

    /** 菜单类型（菜单） */
    const TYPE_MENU = "C";

    /** 菜单类型（按钮） */
    const TYPE_BUTTON = "F";
    /** 菜单类型（页面） */
    const TYPE_PAGE = "P";

    /*=====用户=====*/
    /**
     * 用户名长度限制
     */
    const USERNAME_MIN_LENGTH = 2;
    const USERNAME_MAX_LENGTH = 20;

    /**
     * 密码长度限制
     */
    const PASSWORD_MIN_LENGTH = 5;
    const PASSWORD_MAX_LENGTH = 20;

    /** 正常状态 */
    const USER_NORMAL = "1";

    /** 用户封禁状态 */
    const USER_DISABLE = "0";

    /** 数据范围 */
    /**
     * 全部数据权限
     */
    const DATA_SCOPE_ALL = "1";

    /**
     * 自定数据权限
     */
    const DATA_SCOPE_CUSTOM = "2";

    /**
     * 本部门数据权限
     */
    const DATA_SCOPE_DEPT = "3";

    /**
     * 本部门及以下数据权限
     */
    const DATA_SCOPE_DEPT_AND_CHILD = "4";

    /**
     * 仅本人数据权限
     */
    const DATA_SCOPE_SELF = "5";
}