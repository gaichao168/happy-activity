# 活动随机器

## 项目介绍

## 表
### 1. 用户表

```mysql
   create table test.fwh_users(
    id int unsigned not null primary key auto_increment,
    company_num varchar(255) not null comment '公司编号',
    username varchar(255) not null comment '用户名称',
    created_at timestamp null default null,
    updated_at timestamp null default null,
    unique key account_idx (`company_num`)
) character set utf8mb4 collate utf8mb4_unicode_ci;
```
### 2. 活动表
    
```mysql
   create table test.fwh_activity(
    id int primary key auto_increment,
    user_id int not null comment '创建人',
    name varchar(255) not null comment '活动名称',
    status tinyint(3) unsigned not null default 0 comment '活动状态 0:未开始 1:进行中 2:已结束',
    user_num tinyint(3) unsigned not null default 0 comment '参与人数',
    created_at timestamp null default null,
    updated_at timestamp null default null,
    key user_id_idx (`user_id`)
) character set utf8mb4 collate utf8mb4_unicode_ci;
```
### 3. 活动参与人员表

```mysql
   create table test.fwh_activity_user(
    id int primary key auto_increment,
    activity_id int not null comment '活动id',
    user_id int not null comment '参与人id',
    created_at timestamp null default null,
    updated_at timestamp null default null,
    key activity_id_idx (`activity_id`),
    key user_id_idx (`user_id`),
    unique key activity_id_user_id_unique (`activity_id`, `user_id`)
) character set utf8mb4 collate utf8mb4_unicode_ci;
```
### 4. 活动上场人员分数表

```mysql
    create table test.fwh_activity_user_score(
        id int primary key auto_increment,
        activity_id int not null comment '活动id',
        activity_user_id int not null comment '活动用户',
        total_score int not null comment '分数',
        created_at timestamp null default null,
        updated_at timestamp null default null,
        key activity_id_idx (`activity_id`),
        key activity_user_id_idx (`activity_user_id`)
    ) character set utf8mb4 collate utf8mb4_unicode_ci;
```
### 5. 给活动上场人员分数表添加分数日志表
```mysql
    create table test.fwh_activity_user_score_log(
        id int primary key auto_increment,
        user_id int not null comment '用户id',
        activity_user_score_id int not null comment '活动用户分数id',
        score int not null default 0 comment '分数',
        created_at timestamp null default null,
        updated_at timestamp null default null,
        key activity_user_score_id_idx (`activity_user_score_id`),
        key user_id_idx (`user_id`),
        unique key activity_user_score_id_user_id_unique (`activity_user_score_id`, `user_id`)
    ) character set utf8mb4 collate utf8mb4_unicode_ci;
```