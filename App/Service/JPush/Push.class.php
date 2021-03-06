<?PHP
namespace App\Service\JPush;

use App\Service\JPush\Push\API;
use App\Service\JPush\Push\Consts;

/**
 * 客户端消息推送服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Push
{
    /**
     * 操作类型
     */
    const OP_RE_JOKE = 0; // 评论笑话
    const OP_RE_CMT  = 1; // 回复评论
    const OP_UP_JOKE = 2; // 笑话点赞
    const OP_UP_CMT  = 3; // 评论点赞

    /**
     * 跳转类型
     */
    const RD_HOME    = 0; // 主页
    const RD_DETAIL  = 1; // 详情页
    const RD_MESSAGE = 2; // 消息业
    const RD_CUSTOM  = 3; // 自定义URL

    /**
     * 推送用户UID
     *
     * @var int
     */
    protected $_pushUserId;

    /**
     * 操作类型
     *
     * @var int
     */
    protected $_opType;

    /**
     * 推送标题
     *
     * @var string
     */
    protected $_title = '讲个笑话吧';

    /**
     * 推送消息内容
     *
     * @var string
     */
    protected $_msgContent;

    /**
     * 笑话ID
     *
     * @var int
     */
    protected $_jokeId;

    /**
     * 笑话作者ID
     *
     * @var int
     */
    protected $_jokeUserId;

    /**
     * 笑话作者名称
     *
     * @var string
     */
    protected $_jokeUserName;

    /**
     * 操作用户ID
     *
     * @var int
     */
    protected $_opUserId;

    /**
     * 操作用户名称
     *
     * @var string
     */
    protected $_opUserName;

    /**
     * 操作用户头像URL
     *
     * @var string
     */
    protected $_opUserAvatar;

    /**
     * 操作用户级别
     *
     * @var int
     */
    protected $_opUserLevel;

    /**
     * 评论或回复的内容
     *
     * @var string
     */
    protected $_opContent;

    /**
     * 评论或回复的源内容
     *
     * @var string
     */
    protected $_sourceContent;

    /**
     * 跳转类型
     *
     * @var string
     */
    protected $_redirectType = self::RD_DETAIL;

    /**
     * 指定跳转的自定义URL地址
     *
     * @var string
     */
    protected $_redirectUrl;

    /**
     * 构造方法
     *
     * @param int $opType
     * @param int $pushUserid
     * @return void
     */
    public function __construct($opType, $pushUserid)
    {
        $this->_opType = $opType;
        $this->_pushUserId = $pushUserid;
    }

    /**
     * 设置消息内容
     *
     * @param string $name
     * @param int $opType
     * @return void
     */
    public function setMsgContent($name, $opType)
    {
        $opTypeStr = '';
        $cateName = '';
        switch ($opType) {
            case self::OP_RE_JOKE :
                $opTypeStr = '评论';
                $cateName = '笑话';
                break;
            case self::OP_RE_CMT :
                $opTypeStr = '回复';
                $cateName = '评论';
                break;
            case self::OP_UP_JOKE :
                $opTypeStr = '赞';
                $cateName = '笑话';
                break;
            case self::OP_UP_CMT :
                $opTypeStr = '赞';
                $cateName = '评论';
                break;
        }

        $this->_msgContent = sprintf('%s%s了您的%s', $name, $opTypeStr, $cateName);
    }

    /**
     * 设置笑话ID
     *
     * @param int $id
     * @return void
     */
    public function setJokeId($id)
    {
        $this->_jokeId = $id;
    }

    /**
     * 设置笑话作者ID
     *
     * @param int $id
     * @return void
     */
    public function setJokeUserId($id)
    {
        $this->_jokeUserId = $id;
    }

    /**
     * 设置笑话作者名称
     *
     * @param string $name
     * @return void
     */
    public function setJokeUserName($name)
    {
        $this->_jokeUserName = $name;
    }

    /**
     * 设置操作用户ID
     *
     * @param int $id
     * @return void
     */
    public function setOpUserId($id)
    {
        $this->_opUserId = $id;
    }

    /**
     * 设置操作用户名称
     *
     * @param string $name
     * @return void
     */
    public function setOpUserName($name)
    {
        $this->_opUserName = $name;

        $this->setMsgContent($name, $this->_opType);
    }

    /**
     * 色沪指操作用户头像URL
     *
     * @param string $url
     * @return void
     */
    public function setOpUserAvatar($url)
    {
        $this->_opUserAvatar = $url;
    }

    /**
     * 设置操作用户级别
     *
     * @param int $level
     * @return void
     */
    public function setOpUserLevel($level)
    {
        $this->_opUserLevel = $level;
    }

    /**
     * 设置评论或回复的内容
     *
     * @param string $content
     * @return void
     */
    public function setOpContent($content)
    {
        $this->_opContent = $content;
    }

    /**
     * 设置笑话 或 评论 的源内容
     *
     * @param string $content
     * @return void
     */
    public function setSourceContent($content)
    {
        $this->_sourceContent = $content;
    }

    /**
     * 设置跳转类型
     *
     * @param int $type
     * @return void
     */
    public function setRedirectType($type)
    {
        $this->_redirectType = $type;
    }

    /**
     * 设置跳转的自定义URL
     *
     * @param string $url
     * @return void
     */
    public function setRedirectUrl($url)
    {
        $this->_redirectUrl = $url;
    }

    /**
     * 执行推送命令
     *
     * @return void
     */
    public function fire()
    {
        $pushApi = new API;
        $pushApi->setPlatform(Consts::PF_ANDROID);
        $pushApi->setAudienceAlias(md5($this->_pushUserId));
        $pushApi->setMessageTitle($this->_title);
        $pushApi->setMessageContent($this->_msgContent);
        $pushApi->setMessageExtras(array(
            'user_id_alias' => md5($this->_pushUserId),
            'joke_id' => (int) $this->_jokeId,
            'joke_user_id' => (int) $this->_jokeUserId,
            'joke_user_name' => $this->_jokeUserName,
            'op_type' => $this->_opType,
            'op_user_id' => (int) $this->_opUserId,
            'op_user_name' => $this->_opUserName,
            'op_user_avatar' => $this->_opUserAvatar,
            'op_user_level' => (int) $this->_opUserLevel,
            'op_content' => $this->_opContent ? : $this->_msgContent,
            'op_time' => date('Y-m-d H:i:s'),
            'source_content' => $this->_sourceContent,
            'redirect_type' => (int) $this->_redirectType,
            'redirect_url' => $this->_redirectUrl
        ));

        $pushApi->run();
    }
}
