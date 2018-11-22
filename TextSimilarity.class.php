<?php
/*
*   文本相似度（余弦定理）
*
*   Author:宋小北（@xiaobeicn）
*
*   参考：
*   http://www.ruanyifeng.com/blog/2013/03/cosine_similarity.html
*       http://my.oschina.net/BreathL/blog/42477
*
*   Use:
*   $obj = new TextSimilarity ($text1, $text2);
*   echo $obj->run();
*/

class TextSimilarity
{
    /**
     * [排除的词语]
     *
     * @var array
     */
    private $_excludeArr = array('的','了','和','呢','啊','哦','恩','嗯','吧');
    
    /**
     * [词语分布数组]
     *
     * @var array
     */
    private $_words = array();
    
    /**
     * [分词后的数组一]
     *
     * @var array
     */
    private $_segList1 = array();
    
    /**
     * [分词后的数组二]
     *
     * @var array
     */
    private $_segList2 = array();
    
    /**
     * [分词两段文字]
     *
     * @param [type] $text1 [description]
     * @param [type] $text2 [description]
     */
    public function __construct($text1, $text2)
    {
        $this->_segList1 = $this->segment($text1);
        $this->_segList2 = $this->segment($text2);
    }
    
    /**
     * [外部调用]
     *
     * @return [type] [description]
     */
    public function run()
    {
        $this->analyse();
        $rate = $this->handle();
        return $rate ? $rate : 'errors';
    }
    
    /**
     * [分析两段文字]
     */
    private function analyse()
    {
        //t1
        foreach ($this->_segList1 as $v) {
            if (!in_array($v, $this->_excludeArr)) {
                if (!array_key_exists($v, $this->_words)) {
                    $this->_words[$v] = array(1 , 0);
                } else {
                    $this->_words[$v][0] += 1;
                }
            }
        }
        
        //t2
        foreach ($this->_segList2 as $v) {
            if (!in_array($v, $this->_excludeArr)) {
                if (!array_key_exists($v, $this->_words)) {
                    $this->_words[$v] = array(0 , 1);
                } else {
                    $this->_words[$v][1] += 1;
                }
            }
        }
    }
    
    /**
     * [处理相似度]
     *
     * @return [type] [description]
     */
    private function handle()
    {
        $sum = $sumT1 = $sumT2 = 0;
        foreach ($this->_words as $word) {
            $sum    += $word[0] * $word[1];
            $sumT1  += pow($word[0], 2);
            $sumT2  += pow($word[1], 2);
        }
        
        $rate = $sum / (sqrt($sumT1 * $sumT2));
        return $rate;
    }
    
    /**
     * [分词  【http://www.xunsearch.com/scws/docs.php#pscws23】]
     *
     * @param [type] $text [description]
     *
     * @return [type] [description]
     *
     * @description 分词只是一个简单的例子，你可以使用任意的分词服务
     */
    private function segment($text)
    {
        $outText = array();
        //实例化
        $so = scws_new();
        //字符集
        $so->set_charset('utf8');
        //处理
        $so->send_text($text);
        
        //便利出需要的数组
        while ($res = $so->get_result()) {
            foreach ($res as $v) {
                $outText[] = isset($v['word']) ? strtoupper($v['word']) : "";
            }
        }
        //关闭
        $so->close();
        
        return $outText;
    }
}
