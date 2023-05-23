
select * from userpassword where userid in ('megala','admin','keer')
select * from user_login_history where userid in ('megala','admin') 
select * from userdetails where userid in ('megala','admin')
select * FROM actsection
select * from userpassword where userid='admin'
select * from user_login_history where userid='admin' 
select * from userdetails where userid='admin' 
SELECT * from applicationsrno where establishcode='1' and appltypeshort='R' and applyear='2023'
select * from options where linkname='applicationreceived'

select * from nametitle where linkname='applicationreceived'

select establishcode,establishname,defaultdisplay from establishment where userid='admin'
SELECT * from actsection
select * from applicationtype where appltypedesc not in ('MFA','RFA','Writ Pition','WA')

--ALTER TABLE applicationtype appltypeshort character varying(8) ;
--ALTER TABLE applicationtype ALTER COLUMN appltypeshort TYPE character varying(8)

SELECT * from applicationtype where appltypecode>='11' and appltypecode <='14' order by appltypecode

insert into table applicationtype values('Criminal Revision Petition' CRL.RP
''
--truncate table ungroupapplication

----DROP VIEW connectedappldtls CASCADE;
connectedappldtls 
CASCADE
ccapplicationsummary
SELECT * from unapplication where applicationid='' unapplicationid='UA/7/2022'
SELECT * from unapplication limit 10 where  unapplicationid='WP/1/2020'
select applicationrestore displayboard iascrutiny recordapplication ungroupapplication

--$2y$10$GNMwZVdewv6Z2SgrSY3i5ODtQlp1pg7mt0L.JjJdiwEirUmdPwGvG  --103 admin password

------ $2y$10$z.kW6lxsrL2LE4X0LnITMOtG5UX9gqjbXIx.I0LVDSrrw2SKOcJOu---- old 

--$2y$10$T3ddrWgHg9UFLLxGvp08Bucfnhivz34Oa2WmBfwpi4xpUB/fGAHoC
--$2y$10$nNHYImCC2i2N0RCQlI64ruBF0X782i2OKlT7koCSE5agucAhLSGxy
--$2y$10$S2NR/fxdL0f3iY6FyFfQFuHNRHYpHr7zcSsoeRqB/y8qcka9Iha3G

SELECT * from applicationsrno where appltypeshort='CRL.RP'

--DATE applicationsrno set nextnumber='90000' where  appltypeshort='CRL.RP' and establishcode='1' and applyear='2023' a
INSERT into applicationsrno values('2023','CRL.RP','1','90000','10000',90000)
ALTER table applicationsrno alter column  appltypeshort type varchar(8)
0
--date options set subtitle=null where optioncode=135

--LETE from applicant where applicationid='CRL.RP/90000/2023'

select * from options where linkname='FreshApplication1' 135 Application Filing_old FreshApplication1
select * from application where createdon is not null order by createdon desc limit 100
where applicationdate='2023-04-11' limit 1

select * from advocate
select count(*) as applcount from application where ((applicationsrno between '".$startno."' and '".$endno."') or  (applicationtosrno between '".$startno."' and '".$endno."')) and applicationyear='".$applYear."' and appltypecode='".$applType."'


INSERT into actsection values('1','5','Section-18','2019-04-25 00:00:00','register')
select * from receipt where receiptdate='2023-04-13'

select * from causelisttemp

SELECT causelistdate FROM public.causelisttemp where establishcode=1 and  postedtocourt is not null ORDER BY causelistdate LIMIT 1

SELECT * from  causelisttemp  where causelistdate='2023-04-14'									 



