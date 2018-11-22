## 通过余弦定理+分词计算文本相似度PHP版

---

### 参考：
* http://www.ruanyifeng.com/blog/2013/03/cosine_similarity.html 
* http://my.oschina.net/BreathL/blog/42477
  	
### Use:

```php
<?php

$obj = new TextSimilarity ($text1, $text2);
echo $obj->run();

```
