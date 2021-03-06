<?PHP
namespace App\Model;

use \Think\Model;

/**
 * 模型抽象类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AbstractModel extends Model
{
    /**
     * 获取单条数据
     *
     * @param int $id
     * @return mixed
     */
    public function getData($id)
    {
        return $this->where(
            array('id' => $id)
        )->find();
    }

    /**
     * 根据ID列表批量获取数据
     *
     * @param array $ids
     * @return mixed
     */
    public function getDataByIds($ids)
    {
        return $this->where(
            array(
                'id' => array('IN', $ids)
            )
        )->select();
    }

    /**
     * 修改操作数
     *
     * @param int $id
     * @param bool $isAct
     * @param string $fieldName
     * @return mixed
     */
    public function modifyActionCount($id, $isAct, $fieldName)
    {
        $m = $this->where(
            array('id' => $id)
        );

        if ($isAct) {
            $stat = $m->setInc($fieldName);
        } else {
            $stat = $m->setDec($fieldName);
        }

        return $stat;
    }
}
