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

drop table if exists collections cascade;

create table collections
(
    id_user     bigint not null constraint fk_collections_user
                        references public.user(id)
                        on delete cascade on update cascade,
    id_game     bigint not null,
    id_platform bigint not null,
    constraint fk_collections_games_platforms foreign key (id_game, id_platform)
    references games_platforms (id_game, id_platform)
    on delete no action on update cascade,
    constraint pk_collections primary key (id_user, id_game, id_platform)
);

drop table if exists followers cascade;

create table followers
(
    id_follower bigint not null constraint fk_followers_userFollower
                        references public.user(id)
                        on delete cascade on update cascade,
    id_followed bigint not null constraint fk_followers_userFollowed
                        references public.user(id)
                        on delete cascade on update cascade,
    constraint pk_followers primary key (id_follower, id_followed)
);

drop table if exists votes;

create table votes
(
    id_voter bigint not null constraint fk_votes_userVoter
                        references public.user(id)
                        on delete no action on update cascade,
    id_voted bigint not null constraint fk_votes_userVoted
                        references public.user(id)
                        on delete no action on update cascade,
    positive boolean not null,
    constraint pk_votes primary key (id_voter, id_voted)
);

drop table if exists groups;

create table groups
(
    id bigserial constraint pk_groups primary key,
    name varchar(255) not null,
    id_game     bigint not null,
    id_platform bigint not null,
    constraint fk_groups_games_platforms foreign key (id_game, id_platform)
    references games_platforms (id_game, id_platform)
    on delete no action on update cascade
);

drop table if exists members cascade;

create table members
(
    id_group bigint not null constraint fk_members_groups
                    references groups(id)
                    on delete no action on update cascade,
    id_user bigint not null constraint fk_members_user
                    references public.user(id)
                    on delete no action on update cascade,
    accepted boolean not null,
    constraint pk_members primary key (id_group, id_user)
);
