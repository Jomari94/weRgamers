drop table if exists session;

create table session
(
    id char(40) not null primary key,
    expire integer,
    data BYTEA
);

-- Hacer migración de yii2-user antes de inyectar este sql
alter table profile
    add column gender varchar(255);

drop table if exists games cascade;

create table games
(
    id         bigserial    constraint pk_games primary key,
    name       varchar(255) not null constraint uq_games_name unique,
    genre      varchar(255),
    released   date,
    developers varchar(255)
);

drop table if exists platforms cascade;

create table platforms
(
    id bigserial constraint pk_platforms primary key,
    name varchar(50) not null constraint uq_platforms_name unique
);

drop table if exists games_platforms cascade;

create table games_platforms
(
    id_game     bigint not null constraint fk_games_platforms_games
                        references games(id)
                        on delete cascade on update cascade,
    id_platform bigint not null constraint fk_games_platforms_platforms
                        references platforms(id)
                        on delete cascade on update cascade,
    constraint pk_games_platforms primary key (id_game, id_platform)
);
