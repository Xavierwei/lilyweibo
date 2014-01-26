# JSON data

### Action: scarf/list 围巾数据列表，GET方法

发送数据
* page //第几页
* pagenum //每页显示几个
* status (int, 可选)（只有管理员可以传入status值）

```
[
    {
        "cid": 1, // scarf id
        "content": "Some Text", // 用户输入的文本
        "style": 1, // 用户选择的围巾风格
        "image": "upload/01.jpg", //服务器端生成的文字和预设图片合并后的图片
        "rank": 100, //当前内容的排名名次
        "status": 1, //状态，这个字段之后当管理员用户访问的时候再返回，需要做权限验证！
        "user": { // 子数组：用户节点
            "screen_name": "Someone", //微博用户名 
            "avatar": "http://..." //头像
        }
    },
    {
        "cid": 2,
        "content": "Some Text",
        "style": 3,
        "image": "upload/01.jpg",
        "rank": 101,
        "status": 1,
        "user": {
            "screen_name": "Someone",
            "avatar": "http://..."
        }
    }
]
```

### Action: scarf/rank 获取排名列表，GET方法

发送数据
* page //第几页
* pagenum //每页显示几个
* status (int, 可选)（只有管理员可以传入status值）

```
[
    {
        "cid": 1, // scarf id
        "content": "Some Text", // 用户输入的文本
        "style": 1, // 用户选择的围巾风格
        "image": "upload/01.jpg", //服务器端生成的文字和预设图片合并后的图片
        "rank": 100, //当前内容的排名名次
        "status": 1, //状态，这个字段之后当管理员用户访问的时候再返回，需要做权限验证！
        "user": { // 子数组：用户节点
            "screen_name": "Someone", //微博用户名
            "avatar": "http://..." //头像
        }
    },
    {
        "cid": 2,
        "content": "Some Text",
        "style": 3,
        "image": "upload/01.jpg",
        "rank": 101,
        "status": 1,
        "user": {
            "screen_name": "Someone",
            "avatar": "http://..."
        }
    }
]
```

### Action: scarf/search 根据关键词(微博screen_name, content)搜索，GET方法

发送数据
* page //第几页
* pagenum //每页显示几个
* status (int, 可选)（只有管理员可以传入status值）

```
[
    {
        "cid": 1, // scarf id
        "content": "Some Text", // 用户输入的文本
        "style": 1, // 用户选择的围巾风格
        "image": "upload/01.jpg", //服务器端生成的文字和预设图片合并后的图片
        "rank": 100, //当前内容的排名名次
        "status": 1, //状态，这个字段之后当管理员用户访问的时候再返回，需要做权限验证！
        "user": { // 子数组：用户节点
            "screen_name": "Someone", //微博用户名
            "avatar": "http://..." //头像
        }
    },
    {
        "cid": 2,
        "content": "Some Text",
        "style": 3,
        "image": "upload/01.jpg",
        "rank": 101,
        "status": 1,
        "user": {
            "screen_name": "Someone",
            "avatar": "http://..."
        }
    }
]
```



### Action: scarf/myrank 获得当前用户的排名，GET方法

发送数据（只有管理员可以传入get值）

返回数据

```
[
    {
    	"screen_name": "Someone", //微博用户名
        "avatar": "http://..." //头像
        "rank": 100 //当前用户的排名
    }
]
```

### Action scarf/getimage  生成预览图片，POST方法

发送数据

* content (string, 必填)
* style (int, 必填)

返回数据

```
[
    {
        "image": "upload/01.jpg" //服务器端生成的文字和预设图片合并后的图片
    }
]
```


### Action scarf/post  新增数据，POST方法

发送数据

* content (string, 必填)
* style (int, 必填)
* image (string,必填) 图片路径

返回数据

```
[
    {
        "cid": 0, // scarf id
        "content": "Some Text", // 用户输入的文本
        "style": 1, // 用户选择的围巾风格
        "image": "upload/01.jpg", //服务器端生成的文字和预设图片合并后的图片
        "rank": 100, //当前内容的排名名次        
        "user": { // 子数组：用户节点
            "screen_name": "Someone", //微博用户名
            "avatar": "http://..." //头像
        }
    }
]
```


### Action scarf/put 更新数据（管理员权限)， POST方法
发送数据

* cid (int, required) // scarf的id
* status (int, required) // 更新后的状态

返回数据

```
[
    {
        "cid": 0, // scarf id
        "content": "Some Text", // 用户输入的文本
        "style": 1, // 用户选择的围巾风格
        "image": "upload/01.jpg", //服务器端生成的文字和预设图片合并后的图片
        "rank": 100, //当前内容的排名名次
        "status": 1, //状态
        "user": { // 子数组：用户节点
            "screen_name": "Someone", //微博用户名
            "avatar": "http://..." //头像
        }
    }
]
```



### Action scarf/dmx 大冒险，随机改变当前用户的排名，GET方法

```
[
    {
        "offset": "+40", // 随机增加或减少的名次
        "rank": 100 // 新的排名名次
    }
]
```


### Action scarf/share 分享给好友, POST方法
发送数据

* cid (int, required) // scarf的id
* friend_sns_id (int, required) // 好友id

保存前需要做验证，这个好友是否已经分享过了，如果是，报错。


返回数据

```
[
    {
        "offset": "+10", // 增加10个排名
        "rank": 100 // 新的排名名次
    }
]
```


### Action user/login  微博callback的方法。用户登陆，如果第一次登陆，在数据库新增纪录。




