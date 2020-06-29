### 工欲善其事必先利其器
- 编码水平再高，如果没有一个工具全面的框架，短时间也很难发挥作用。
- 知错是可贵的，但知错不改，绝对是灾难。垃圾代码也不是一天两天堆成的
- 我觉得一个方法或者函数，就只有一个功能，多了请分开

### easyswoole demo
> 主要使用了文档里推荐的模块，然后将其封装起来。当作一个管理后台系统
> 前端页面还没有写

#### 目前实现
1. 添加了jwt auth
2. 将crontab定时任务通过接口，查，执行，停止，重新使用
3. redis 查询（暂时），可以加入修改 一般不太建议手动操作
4. 将官方的task封装成接口，方便调用，（按道理可以写api，为什么还多次一举，使用task可以异步执行耗时任务）
5. 数据库池加载、redis池加载 ，系统启动加载封装在App\Provide中
6. 常见异常统一

#### 后续继续
1. elasticsearch 使用
2. 封装一些orm方法以及分页，目前分页有了雏形，详情请看redis查询
3. 通过命令行形式使用消息队列服务，支持平滑重启，协程支持
4. http client封装接口，并集成错误日志输出
5. 第三方日志类集成
6. easyswoole 官方的phpunit引入使用
...

