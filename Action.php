<?php

class GoodLuck_Action extends Typecho_Widget implements Widget_Interface_Do
{
    private $db;
    private $options;

    /**
     * GoodLuck_Action constructor.
     * @throws Typecho_Db_Exception
     * @throws Typecho_Plugin_Exception
     */
    public function __construct($request, $response, $params = null)
    {
        parent::__construct($request, $response, $params);
        $this->db = Typecho_Db::get();
        $this->options = Helper::options();
    }

    public function execute()
    {

    }

    public function action()
    {

    }

    /**
     * 手气不错核心函数
     *
     * @access public
     * @return void
     */
    public function goodluck()
    {
        $db = $this->db;

        $sql = $db->select('MAX(cid)')->from('table.contents')
            ->where('status = ?', 'publish')
            ->where('type = ?', 'post')
            ->where('created <= unix_timestamp(now())', 'post');
        $result = $db->fetchAll($sql);
        $max_id = $result[0]['MAX(`cid`)'];//POST类型数据最大的CID

        $sql = $db->select('MIN(cid)')->from('table.contents')
            ->where('status = ?', 'publish')
            ->where('type = ?', 'post')
            ->where('created <= unix_timestamp(now())', 'post');
        $result = $db->fetchAll($sql);
        $min_id = $result[0]['MIN(`cid`)'];//POST类型数据最小的CID

        $result = NULL;
        $rand_ids = Typecho_Cookie::get('contents_rand_ids');//获取最近5篇随机展示文章ID;

        if (!$rand_ids) {
            $rand_ids = [];
        } else {
            $rand_ids = explode(',', $rand_ids);
        }

        $times_out = 0;//计算循环次数
        $target = $this->options->siteUrl;//默认跳转首页

        while ($result == NULL) {
            $times_out++;//循环计数
            $rand_id = mt_rand($min_id, $max_id);

            //查询数据
            $sql = $db->select()->from('table.contents')
                ->where('status = ?', 'publish')
                ->where('type = ?', 'post')
                ->where('created <= unix_timestamp(now())', 'post')
                ->where('cid = ?', $rand_id);

            $result = $db->fetchAll($sql);

            if (in_array($rand_id, $rand_ids)) {
                $result = NULL;
            } else {
                if ($result != NULL) {
                    $rand_ids[] = $rand_id;
                    if (count($rand_ids) == 5) {
                        unset($rand_ids[0]);
                        $rand_ids = array_values($rand_ids);
                    }
                    $rand_ids = implode(',', $rand_ids);
                    Typecho_Cookie::set("contents_rand_ids", $rand_ids);
                    $target = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($result['0']);
                }
            }
            //超过100次不干了
            if ($times_out > 100)
                break;
        }
        header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->response->redirect($target['permalink'], 307);
    }
}
