<?PHP
namespace App\Service;
use App\Service\AbstractService;

/**
 * 笑话逻辑服务
 *
 * @author popfeng <popfeng@yeah.net>
 */
class Joke extends AbstractService
{
    /**
     * 根据ID获取笑话详情 
     *
     * @param int $id
     * @param int $userId
     * @throws Exception
     * @return array
     */
    public function getDetail($id, $userId)
    {
        $detail = (new \App\Model\Joke)->getDetail($id);
        if (empty($detail)) {
            throw new \Exception('空的笑话详情');
        }

        $detail['is_favorate'] = (int) $this->_isFavorate($id, $userId);
        return $detail;
    }

    /**
     * 设置收藏
     *
     * @param int $id
     * @param int $userId
     * @param bool $isFav
     * @return bool
     */
    public function setFavorate($id, $userId, $isFav = true)
    {
        try {
            $model = new \App\Model\JokeFavorateRecord;
            $model->startTrans(); // 开始事务

            // 设置原子数据
            $data = $model->getData($id, $userId);
            if ($data) {
                $model->deleteData($id);
            } else {
                $model->addData($id, $userId, (int) $isFav);
            }

            // 更新统计数据
            (new \App\Model\Joke)->modifyFavorateCount($id, $isFav);

            $model->commit(); //提交事务
        } catch (\Exception $e) {
            $model->rollback(); // 事务回滚
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 是否已收藏
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    private function _isFavorate($id, $userId)
    {
        if ($userId) {
            $model = new \App\Model\JokeFavorateRecord;
            $data = $model->getData($id, $userId);
            return ! empty($data);
        } else {
            return false;
        }
    }
}
