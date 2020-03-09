<?php

return array(

    /*
     * Package URI
     * 后台入口
     * @type string
     */
    'uri' => 'admin',

    /*
     *  Domain for routing.
     *  后台专属域名，可以留空
     *  @type string
     */
    'domain' => '',

    /*
     * Page title
     * 页面名称，在页面标题和左上角显示
     * @type string
     */
    'title' => env('APP_NAME', 'Laravel'),

    /*
     * The path to your model config directory
     * 模型配置信息文件存放目录
     * @type string
     */
    'model_config_path' => config_path('administrator'),

    /*
     * The path to your settings config directory
     *  配置信息文件存放目录
     * @type string
     */
    'settings_config_path' => config_path('administrator/settings'),

    /*
     * The menu structure of the site. For models, you should either supply the name of a model config file or an array of names of model config
     * files. The same applies to settings config files, except you must prepend 'settings.' to the settings config file name. You can also add
     * custom pages by prepending a view path with 'page.'. By providing an array of names, you can group certain models or settings pages
     * together. Each name needs to either have a config file in your model config path, settings config path with the same name, or a path to a
     * fully-qualified Laravel view. So 'users' would require a 'users.php' file in your model config path, 'settings.site' would require a
     * 'site.php' file in your settings config path, and 'page.foo.test' would require a 'test.php' or 'test.blade.php' file in a 'foo' directory
     * inside your view directory.
     *  后台菜单数组，多维数组渲染为多级嵌套菜单
     * @type array
     *
     * 	array(
     *		'E-Commerce' => array('collections', 'products', 'product_images', 'orders'),
     *		'homepage_sliders',
     *		'users',
     *		'roles',
     *		'colors',
     *		'Settings' => array('settings.site', 'settings.ecommerce', 'settings.social'),
     * 		'Analytics' => array('E-Commerce' => 'page.ecommerce.analytics'),
     *	)
     */
    'menu' => [
        '用户与权限' => [
            'users',
            'roles',
            'permissions',
        ],
    ],
    /*
     * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
     * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
     * 权限控制的回调函数
     * 此回调函数需要返回true或者false，用来检测用户是否有权限访问后台
     * `true`为通过，`false`会将页面重定向到`login_path` 选项定义 url 中
     * @type closure
     */
    'permission' => function () {
        // 只要能管理内容的用户，就允许访问后台
        return Auth::check() && Auth::user()->can('manage_contents');
    },

    /*
     * This determines if you will have a dashboard (whose view you provide in the dashboard_view option) or a non-dashboard home
     * page (whose menu item you provide in the home_page option)
     * 使用布尔只来设定是否使用后台主页
     * 如果值为 `true`，将使用 `dashboard_view` 定义的视图文件进行主页渲染
     * 如果值为 `false`, 将使用 `home_page` 定义的视图文件进行主页渲染
     * @type bool
     */
    'use_dashboard' => false,

    /*
     * If you want to create a dashboard view, provide the view string here.
     * 设置后台主页视图文件，由 `use_dashboard` 选项决定
     * @type string
     */
    'dashboard_view' => '',

    /*
     * The menu item that should be used as the default landing page of the administrative section
     * 用来作为后台主页的菜单条目，由 `use_dashboard` 选项决定，菜单指的是 `menu` 选项
     * @type string
     */
    'home_page' => 'users',

    /*
     * The route to which the user will be taken when they click the "back to site" button
     *
     * @type string
     */
    'back_to_site_path' => '/',

    /*
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    'login_path' => 'login',

    /*
     * The logout path is the path where Administrator will send the user when they click the logout link
     *
     * @type string
     */
    'logout_path' => false,

    /*
     * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
     *
     * @type string
     */
    'login_redirect_key' => 'redirect',

    /*
     * Global default rows per page
     *
     * @type int
     */
    'global_rows_per_page' => 20,

    /*
     * An array of available locale strings. This determines which locales are available in the languages menu at the top right of the Administrator
     * interface.
     *
     * @type array
     */
    'locales' => [],

    'custom_routes_file' => app_path('Http/routes/administrator.php'),
);
