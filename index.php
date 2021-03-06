<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if (version_compare(PHP_VERSION,'5.3.0','<')) die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 关闭自动生成目录安全文件
define('BUILD_DIR_SECURE', false);

// 入口绑定
define('BIND_MODULE', 'App');

// 定义应用目录
define('APP_PATH', realpath('./') . '/');

// 定义公共模块目录
define('COMMON_PATH', APP_PATH . 'Common/');

// 定义公共第三方类库目录
define('ADDONS_PATH', COMMON_PATH . 'Addons/');

// 定义运行时目录
define('RUNTIME_PATH', APP_PATH . 'Runtime/');

// 引入ThinkPHP入口文件
require realpath('../core/ThinkPHP') . '/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
