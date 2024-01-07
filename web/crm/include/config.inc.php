<?php
    //database configuration variables
    // define('DB_HOST', 'localhost');  // hostname of database
    // define('DB_HOST', '192.168.240.3'); 
    define('DB_HOST', getenv('DB_HOST')); 
    echo 'Here:';
    echo getenv('DB_USERNAME');
    define('DB_USERNAME', getenv('DB_USERNAME'));   // username of database
    define('DB_PASSWORD', getenv('DB_PASSWORD'));       // password of databsae
    define('DEFAULT_DB', getenv('DEFAULT_DB'));     // database of database
    define('DB_TYPE', 'mysql');         // type of databsae
    define('DB_USE_PCONNECT', 1);     // set this to 0 if your database doesn't support persistent connections

    //用户权限
    define('ADMIN',1);//1-管理员，2-总代理，3-代理，4-代购，5-秘书
    define('GENERAL_AGENT',2);//general agent
    define('AGENT',3);//agent
    define('PURCHASE',4);//purchase
    define('SECRETARY',5);//secretary, super xiaomi
    define('XIAOMI',6);//xiao mi
  
    //特殊小蜜点位
    define('XIAOMI001',1016);//secretary 001

    //min and max password and username lengths
    define('MIN_PASSWORD_LENGTH',4);  
    define('MAX_PASSWORD_LENGTH',13);
    define('MIN_USERNAME_LENGTH',4);
    define('MAX_USERNAME_LENGTH',13);
    define('PAGESIZE',20);
  
    define('COOKIESTRING','Mica7Is0Aways5Right');
  
    $strings = array(
  	    'TABLE_TITLE_BKCOLOR'=>"bgcolor=#A5C2E0",
  	    'TABLE_DARK_COLOR'=>"bgcolor='#E2ECF5'",
        'USERNAME' => '用户名',
        'PASSWORD' => '密&nbsp;&nbsp;&nbsp;&nbsp;码',
        'ERROR_PASSWORD_INCORRECT' => '密码不正确',
    );
  
    $strings = array(
        'TYPE_MAKER'=>"######",
        'TABLE_LINK_BK_COLOR'=>"bgcolor=#6699CC",
        'TABLE_TITLE_BKCOLOR'=>"bgcolor=#A5C2E0",
	    'TABLE_REMARK_BKCOLOR'=>"bgcolor=#C5D8ED",
        'TABLE_DARK_COLOR'=>"bgcolor='#E2ECF5'",
        'TABLE_LIGHT_COLOR'=>"bgcolor='#F2F7FB'",
        'DARK_OVER'=>' onMouseOver="bgColor=\'#FFFFFF\'" onMouseOut="bgColor=\'#E2ECF5\'"',
	    'LIGHT_OVER'=>' onMouseOver="bgColor=\'#FFFFFF\'" onMouseOut="bgColor=\'#F2F7FB\'"',
            
	    'USERNAME' => '用户名',
        'PASSWORD' => '密&nbsp;&nbsp;&nbsp;&nbsp;码',
             
        'RETURN' =>'返回上一页',
        'RETURN_LIST' => '返回列表',
        'RETURN_CLICK' => 'window.history.back(-1);',
        'BUTTON_RETURN'=> '<input type="button" name="Submit" value="返回上一页" onclick="window.history.back(-1);">',
        'MESSAGE_BOX' => '信息提示窗口',
        'MESSAGE_ERR_BOX' =>'错误信息提示窗口',
             
        'ERROR_WRONG_PASSWORD'=>'用户名或密码错误，请重新输入',
        'ERROR_PASSWORD_INCORRECT' => '密码不正确',
	    'ERROR_USERNAME_TAKEN' => '用户名已经存在',
	           
	    'ERROR_PASSWORD_TOO_SHORT' => '密码太短',
	    'ERROR_PASSWORDS' => '密码不合法',
	    'ERROR_USERNAME_TOO_SHORT' => '用户名太短',

        'LOG_LOGIN_FAIL' => '登录系统未成功',
        'LOG_LOGIN_SUCCESS' => '成功登录系统',
        'LOG_LOGOUT'	=> '退出系统',
        'LOG_TITLE'	=> '系统登录记录',
        'LOG_IP'	=> 'IP地址',
        'LOG_USERNAME'	=> '用户',
        'LOG_EVENT'	=> '事件',
        'LOG_DATE'	=> '时间',

        'HELP_CHANGE_PASSWORD' => '修改您的密码,密码长度必需大于4位',

        'MENU_HELLO' => '您好! ',
        'MENU_CHANGE_PASSWORD' => '修改密码',
        'MENU_LOGOUT' => '退出系统',
        'MENU_ADD_USER' => '增加用户',
        'MENU_EDIT_USER' => '查看/修改用户',
        'MENU_VIEW_SECURITY_LOG' => ' 查看用户日志',
             
        'USER_SUBMIT_ADD' =>'增加新用户',
        'USER_SUBMIT_EDIT' =>'修改此用户信息',
        'USER_SUBMIT_DELE' =>'删除此用户信息',
        'USER_SUBMIT_ADD_TYPE' =>'用户权限管理',
        'USER_SUBMIT_TYPE'=>'继续增加用户权限',
        'USER_SUBMIT_EDIT_TYPE' =>'修改用户权限',
        'USER_TYPE_CHANGED' => '用户权限修改成功',
        'USER_INFO' =>'用户信息',
        'USER_PASSWORD_CHANGED' => '修改用户密码成功',
        'USER_PASSWORD_NOT_CHANGED' => '修改用户密码失败',
        'USER_OLD_PASSWORD' => '旧密码',
        'USER_NEW_PASSWORD' => '新密码',
        'USER_CONFIRM_NEW_PASSWORD' => '确认新密码',
        'USER_INVALID_EMAIL' => '您的EMAIL格式不正确',
        'USER_SUCCESS' => '用户添加成功',
        'USER_PASSWORD' => '输入用户密码',
        'USER_PASSWORD_CONFIRM' => '确认用户密码',
        'USER_CHANGE_PASSWORD' => '修改密码时填写,修改时必须输入输入旧密码，新密码，确认新密码三项',
        'USER_EMAIL' => 'Email地址',
        'USER_TELEPHONE' => '电话' ,
        'USER_MOBILE' => '手机',
        'USER_MENU' => '用户管理菜单',
	    'USER_TYPE' => '用户权限',
	    'USER_DELETED' => '成功删除用户',
	    'USER_USERS' => '用户列表',
	    'USER_ID' => '用户ID',
	    'USER_DATE_ADDED' => '用户注册日期',
	    'USER_TYPE' => '用户权限',
	    'USER_EDIT' => '编辑',
	    'USER_DELETE' => '删除',
	    'USER_VIEW' => '查看',
	    'USER_EDIT_TYPE' => '修改权限',
	    'USER_CONFIRM_DELETE' => '您确定要删除这个用户吗',
	    'USER_IPMAP' => '管理IP地址',
	    'USER_TITLE_ADD' => '增加新用户 ',
	    'USER_TITLE_EDIT' => '修改用户信息',
	    'USER_TITLE_DELE' => '删除用户信息',
	    'USER_TITLE_TYPE' => '用户权限配置',
	    'USER_NO_RESULTS' => '无符合条件用户',
	    'USER_USERNAME' => '用户名',
	    'USER_REALNAME' =>'真实姓名',
	    'USER_UPDATED' => '用户资料修改成功',
	    'USER_NOTEXIST' => '用户不存在',
	    'USER_OPERATE' => '操作',

        'USER_TYPE_LIST'=>array('1'=>'管理员',
	                            '2'=>'总代理',
	                            '3'=>'代理',
	                            '4'=>'代购',
	                            '5'=>'超级秘书',
	                            '6'=>'秘书'
                                ),
               
        'SEARCH_VIEW_RESULT' => '搜寻结果',

        'CUMVIEW_RETURN' => '返回',
        'CUMVIEW_PRINT' => '打印',
        'BUTTON_SUBMIT' => '提交',
             
        'COLUMN_TITLE'=>'栏目权限',
        'COLUMN_ALL'=>'所有',
        'COLUMN_TR'=>'10',
        'COLUMN_TD'=>'5',
        'COLUMN_LIMIT'=>'50',
        'COLUMN_CNAME' =>'栏目中文名称',
        'COLUMN_ENAME' =>'栏目英文名称',
             
             
        'MARKER_PROCESS'=>array('1'=>'直接包含'
                               ),
                                    
        'UPLOAD_ERR'=>array('0'=>'文件上传成功!',
                            '1'=>'文件上传失败:上传的文件太大!',
                            '2'=>'文件上传失败:上传的文件太大!',
                            '3'=>'文件上传失败:文件只有部分被!',
                            '4'=>'文件上传失败:没有文件被上传!',
                            '5'=>'文件上传失败:文件类型错误或上传的文件太大! '
                            ),
        'MAX_FILE_SIZE'=>'104857600',

        //application/vnd.rn-realmedia
        'UPLOAD_TYPE_MULTI'=>array('avi'=>'avi',
                                   'swf'=>'swf',
                                   'rm'=>'rm',
                                   'mp2'=>'mp2',
                                   'mp3'=>'mp3',
                                   'mpga'=>'mpga',
                                   'ram'=>'ram',
                                   'ra'=>'ra',
                                   'wav'=>'wav',
                                   'qt'=>'qt',
                                   'mov'=>'mov',
                                   'movie'=>'movie',
                                   'mpe'=>'mpe',
                                   'mpeg'=>'mpeg',
                                   'mpg'=>'mpg',
                                   'wma'=>'wma',
                                   'wmv'=>'wmv'
                                   ),                            
        'UPLOAD_TYPE_PIC'=>array('gif'=>'image/gif',
                                 'jpeg'=>'image/jpeg',
                                 'jpg'=>'image/jpeg',
                                 'jpe'=>'image/jpeg',
                                 'png'=>'image/png'
                                 )        
	);
                                 
	$menuname=array("HOME"=>"返回首页",
				    "USER"=>array("USERMANAGER" =>"用户管理",
				                  "USERCREATE" =>"创建新用户")
                	);

	$menulink=array("HOME"=>"/index_logon.php",
				    "USER"=>array("USERMANAGER" =>"/user/list.php",
				                  "USERCREATE" =>"/user/create.php")
                    );

	$menuico=array("HOME"=>"/images/home.gif",
				    "USER"=>array("USERMANAGER" =>"/images/node.gif",
				                  "USERCREATE" =>"/images/new.gif")
                    );
                
	$fieldcontentarray = array("MAINTITLE" => "getMaintitle()",
								"CONTENT" => "getContent()"
								);
