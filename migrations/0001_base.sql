drop table if exists participants;
drop table if exists affiliates;

create table if not exists `versions` (
    `id`      bigint unsigned not null auto_increment,
    `name`    varchar(255)    not null,
    `created` timestamp default current_timestamp,
    primary key (id)
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_general_ci;

create table if not exists `affiliates` (
    `id`         bigint unsigned not null auto_increment,
    `name`       varchar(255)    not null,
    `start_date` timestamp,
    primary key (id)
)
    engine = innodb
    auto_increment = 0
    character set utf8
    collate utf8_general_ci;

create table if not exists `participants` (
    `entity_id`     bigint unsigned not null auto_increment,
    `firstname`     varchar(255)    not null,
    `lastname`      varchar(255)    not null,
    `email`         varchar(255)    not null,
    `position`      varchar(255),
    `shares_amount` int,
    `start_date`    timestamp,
    `parent_id`     bigint unsigned,
    primary key (entity_id),
    foreign key (parent_id) REFERENCES affiliates (id)
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_general_ci;