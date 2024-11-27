<?php
/**
 * @Date: 2024/11/27 15:54
 */
declare(strict_types=1);
namespace app\common\services\system;


use app\common\dao\system\LoginLogsDao;
use app\common\helper\ArrayHelper;
use app\common\services\BaseService;
use think\facade\Db;
use think\facade\Log;

/**
 * 登录日记
 * Class LoginLogsService
 */
class LoginLogsService extends BaseService
{

    /**
     * LoginLogsService constructor.
     * @param LoginLogsDao $dao
     */
    public function __construct(LoginLogsDao $dao)
    {
        $this->dao = $dao;
    }


    public function getList($params = []){
        [$page, $limit] = $this->dao->getPageValue();
        $filter = [];
        if (isset($params['source']) && $params['id'] > 0) {
            $filter[] = ['source', '=', $params['source']];
            if($params['source'] == 'admin'){
                $filter[] = ['admin_id', '=', $params['id']];
            }
        }
        return $this->dao->model->where($filter)->order(['create_time DESC'])->page($page)->paginate($limit)->toArray();
    }

    public function getLogData($param = []): array
    {
        $data['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ??  request()->ip();
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        is_array($param) && $param = json_encode($param,JSON_UNESCAPED_UNICODE);
        $data['param_data'] = $param;
        return $data;
    }


    public function createLog($data,$status = 0,$message = '',$content = '')
    {
        if(empty($data['admin_id'])){
           return false;
        }
        $data['status'] = $status;
        $data['message'] = $message;
        $data['content'] = $content;
        $logs = array_merge($data,$this->getLogData(request()->param()));
        Db::startTrans();
        $res[] = $this->dao->model->create($logs);
        if(ArrayHelper::checkArr($res)){
            Db::commit();
            return $res;
        }else{
            Db::rollback();
            Log::write($logs,'error');
            return false;
        }
    }
}