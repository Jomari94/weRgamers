drop table if exists session;

create table session
(
    id char(40) not null primary key,
    expire integer,
    data BYTEA
);

-- Hacer migración de yii2-user antes de inyectar este sql
alter table profile
    add column gender varchar(255),
    add column language varchar(5);

-- Hacer migración de yii2-rbac antes de inyectar este sql
alter table auth_role
    alter column data type text;
alter table auth_item
    alter column data type text;

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
                        on delete no action on update cascade,
    id_platform bigint not null constraint fk_games_platforms_platforms
                        references platforms(id)
                        on delete no action on update cascade,
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
    on delete cascade on update cascade,
    constraint pk_collections primary key (id_user, id_game, id_platform)
);

drop table if exists followers cascade;

create table followers
(
    id_follower bigint not null constraint fk_followers_user_follower
                        references public.user(id)
                        on delete cascade on update cascade,
    id_followed bigint not null constraint fk_followers_user_followed
                        references public.user(id)
                        on delete cascade on update cascade,
    constraint pk_followers primary key (id_follower, id_followed)
);

drop table if exists votes;

create table votes
(
    id_voter bigint not null constraint fk_votes_user_voter
                        references public.user(id)
                        on delete cascade on update cascade,
    id_voted bigint not null constraint fk_votes_user_voted
                        references public.user(id)
                        on delete cascade on update cascade,
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
                    on delete cascade on update cascade,
    accepted boolean not null default false,
    admin boolean not null default false,
    constraint pk_members primary key (id_group, id_user)
);

drop table if exists messages cascade;

create table messages
(
    id          bigserial constraint pk_messages primary key,
    id_sender   bigint not null constraint fk_messages_user_sender
                    references public.user(id)
                    on delete cascade on update cascade,
    content     text,
    seen        boolean default false,
    created     timestamptz default current_timestamp,
    id_conversation bigint not null constraint fk_messages_conversation
                    references conversations(id)
                    on delete cascade on update cascade
);

drop table if exists conversations cascade;

create table conversations
(
    id bigserial constraint pk_conversations primary key,
    id_participant1 bigint not null constraint fk_conversations_user_participant1
                    references public.user(id)
                    on delete cascade on update cascade,
    id_participant2 bigint not null constraint fk_conversations_user_participant2
                    references public.user(id)
                    on delete cascade on update cascade
);

drop table if exists notifications cascade;

create table notifications
(
    id bigserial constraint pk_notifications primary key,
    id_receiver bigint not null constraint fk_notifications_receiver
                    references public.user(id)
                    on delete cascade on update cascade,
    content varchar(250),
    type    varchar(5)
);

drop table if exists events cascade;

create table events
(
    id          bigserial constraint pk_events primary key,
    id_group    bigint constraint fk_events_groups
                    references groups(id)
                    on delete cascade on update cascade,
    inicio      timestamptz default current_timestamp,
    fin         timestamptz,
    activity    varchar(250),
    constraint uq_events_id_group unique (id_group)
);

drop table if exists reviews cascade;

create table reviews
(
    id      bigserial    constraint pk_reviews primary key,
    content varchar(500) not null,
    score   int          not null,
    created date         default current_date,
    id_user bigint       not null constraint fk_reviews_users
                            references public.user(id)
                            on delete cascade on update cascade,
    id_game bigint       not null constraint fk_reviews_games
                            references games(id)
                            on delete cascade on update cascade
);
