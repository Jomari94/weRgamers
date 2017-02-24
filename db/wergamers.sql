drop table if exists session;

create table session
(
    id char(40) not null primary key,
    expire integer,
    data BYTEA
);
