# sqlite

## docs
https://www.sqlite.org/docs.html

## pivot

### sqlite拡張
https://github.com/nalgeon/sqlean

### サンプル実行

- sales.csv import

```sql

-- 行情報
create table years as
select value as year from generate_series(2018, 2021);

-- 列情報
create table quarters as
select value as quarter, 'Q'||value as name from generate_series(1, 4);

-- 拡張インポート
.load extensions/incbator/pivotvtab

create virtual table sales_by_year using pivot_vtab (
  -- rows
  (select year from years),
  -- columns
  (select quarter, name from quarters),
  -- data
  (select revenue from sales where year = ?1 and quarter = ?2)
);

select * from sales_by_year;

```


