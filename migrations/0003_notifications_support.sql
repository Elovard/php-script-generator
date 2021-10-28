ALTER TABLE participants ADD notifications_enabled boolean;

create table if not exists `notifications` (
    `id`     bigint unsigned not null auto_increment,
    `participant_id`     bigint unsigned not null,
    primary key (id)
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_general_ci;

ALTER TABLE notifications ADD CONSTRAINT notifications_participants__fk FOREIGN KEY(participant_id)
    REFERENCES participants(entity_id);