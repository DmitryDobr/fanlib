--
-- PostgreSQL database dump
--

-- Dumped from database version 11.17
-- Dumped by pg_dump version 11.17

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: USER; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."USER" VALUES (2, '1999-01-28', 'Привет :)
Пишу детективные истории. В основном про расследование убийств. Сейчас пишу рассказ "Второе письмо". Буду рад вашей критике.', 'JohnDoe@mail.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'John Doe');
INSERT INTO public."USER" VALUES (5, '2023-12-01', 'Я люблю пиво', 'alcozavr@mail.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'alcozavr');
INSERT INTO public."USER" VALUES (3, '1995-06-30', 'Всем привет!
Я - большой фанат Евангелиона, могу разговаривать за лор часами. Иногда творю собственные фан-рассказы. Надеюсь, читателей не сильно смутят мои отхождения от канона', 'Alal@mail.ru', '25f9e794323b453885f5181f1b624d0b', 'Allacastarк');
INSERT INTO public."USER" VALUES (0, '2004-02-03', 'I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin. I am Admin.', 'dmitrdobr@mail.ru', '2b8f7198614e3d872116d3c2840fd37c', 'Dobr');
INSERT INTO public."USER" VALUES (1, '2003-05-13', '🦊Всем фыр!🦊

Обитаю на этом сайте с 2016 года. Создавая свои работы и читая другие, я не только набираюсь опыта, но и буквально отдыхаю душой от повседневной рутины, как, наверное, многие. К сожалению, писать получается не часто (автор-лентяйка тот ещё🌚), но я всё равно стараюсь радовать всех новыми и интересными произведениями на совершенно различные темы :)
Спасибо вам, читатели, за поддержку и конструктивную критику!
', 'example@mail.ru', '42dae262b8531b3df48cde9cc018c512', 'Одноглазый Лис');
INSERT INTO public."USER" VALUES (4, '2002-02-22', 'Сижу на паре', 'random@ya.ru', '827ccb0eea8a706c4c34a16891f84e7b', 'Гуля Ким');


--
-- Data for Name: WORK; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."WORK" VALUES (0, 0, 'My first work', '2023-09-30', 'My first work', 2, 'My First WOrk');
INSERT INTO public."WORK" VALUES (1, 2, 'Второе письмо было напечатано на листе бумаги (папиросная бумага, вырезка из журнала, помятая, с окурками в углу).  По-видимому, его писали еще до суда, наспех переделанное на машинке, оно походило на «политическое завещание», тем более, что рассказчик особенно не вдумывался в свои слова, считая, видимо, задачу выполненной. 
В конце приписка: ». », которая, возможно, имела и другой смысл — содержание письма и обстоятельства смерти адвоката остались невыясненными.', '2023-11-12', 'Remark', 1, 'Второе письмо . . .');
INSERT INTO public."WORK" VALUES (2, 0, 'This is my second work. Not first!!!', '2023-11-19', 'DADADADADADADADA', 1, 'My Second WOrk');
INSERT INTO public."WORK" VALUES (5, 3, 'Винагардиум левиоссААААААААААААААААААа', '2023-12-08', '', 1, 'Гарик (бульдог) Поттерович и камень в почках');
INSERT INTO public."WORK" VALUES (6, 4, 'да.', '2023-12-09', NULL, 1, 'Что то заумное');
INSERT INTO public."WORK" VALUES (4, 3, 'Что если бы после победы над ангелами произошло что-то еще?', '2023-11-29', 'Привет :)', 1, 'Что если?');


--
-- Data for Name: CHAPTER; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."CHAPTER" VALUES (0, 0, 'Start of stity sfsdfsd fsdf sdf sdf sdf sdfsdf sdfsdf
sd f
sd
 fsd
 f
sdf 
sdf
 sd
f 
sd f', 'just first chapter', 'Начало действия', 0);
INSERT INTO public."CHAPTER" VALUES (1, 0, 'sdfsd fsdf sd fsdf sdf sdf sd fsd fsdf sdf sdf sd fsdf sdf sd fsdf sd
sdf sdf sdf sd fsd fsdf sdf sdf sdf sfsd f
sd f
sd f
sd f
sdf s', 'wait for the end', 'Начало действия', 1);
INSERT INTO public."CHAPTER" VALUES (2, 0, 'All characters died!!!', 'yay, it is final!!', 'Начало действия', 2);
INSERT INTO public."CHAPTER" VALUES (10, 4, 'rerewergthghjrhetrgdb', NULL, 'ewerhytjhkjjuytrf', 0);
INSERT INTO public."CHAPTER" VALUES (6, 5, '- Ты комедиант, Гарик! - громогласно произнес Гагрид Мартиросяныч
- Пасиба!!
- Что пасиба?
- Пасиба учитэл!!!
- Внимательнее будь
Спустя некоторое время.
- Это кто такой?
- эТО?
- Да.
- Это ЭЖИК!!!', NULL, 'Камень, который вышел', 0);
INSERT INTO public."CHAPTER" VALUES (12, 5, '- Это кто такой?
- Это тигар!
- Нет, это тигрица.
- Пачему?
- У нас детский банк, тут не все можно показать
- А, понял
- Это что такой?
- Это? Я не.. ну..
- Это чебурек!
- Пачему чебурек?
- А ты понюхай
- Да, чебурек
- Дальше. Это кто такой?
- Это тигар!
- Нет, тигрица!
- Нет, тигар
- Пачему?
- Вот! (указывает)
- Убедил', NULL, 'Хранитель тиграв', 3);
INSERT INTO public."CHAPTER" VALUES (13, 5, '- Это что такой?
- Это синхрофазатрон!!!
- Где ты видел такой синхрофазатрон?
- А где ты видел НЕ такой синхрофазатрон?
- Убедил', NULL, 'Косой синхрофазатрон', 4);
INSERT INTO public."CHAPTER" VALUES (3, 1, 'Второе письмо было напечатано на листе бумаги (папиросная бумага, вырезка из журнала, помятая, с окурками в углу). По-видимому, его писали еще до суда, наспех переделанное на машинке, оно походило на «политическое завещание», тем более, что рассказчик особенно не вдумывался в свои слова, считая, видимо, задачу выполненной. В конце приписка: ». », которая, возможно, имела и другой смысл — содержание письма и обстоятельства смерти адвоката остались невыясненными.
Вторая версия — о самоубийстве — мне показалась маловероятной.  Я допускаю, конечно, возможность суицида в подобных обстоятельствах, но тогда не объяснялось бы исчезновение обоих писем.  А они исчезли, и к тому же в одно и то же время.  У покойного было слабое сердце, он не мог позволить себе такого напряжения, тем не менее, в одном из них фигурировали пачки долларов, купюры.  Я не был уверен — может быть, речь шла просто о двойной бухгалтерии. Так или иначе, все указывает на самоубийство.  Повторяю — решение было принято быстро. Из вещей больше ничего не осталось.
Я спросил профессора, не сохранилось ли что-нибудь из документов. «Увы, нет»,— ответил он сделал скорбное лицо.  Я положил на стол карандаш, снял очки, протер их носовым платком и подписал «следователь Голдштерн».  «Можете не писать, они все сожжены» и надел фуражку.
Профессор положил ручку. Потом надел очки.  Глаза у него были грустные, усталые.  Через несколько минут разговора, как мне показалось, слезы были и у меня. Одна мысль меня не отпускала: адвокат был не из тех людей, кто покончит с собой из-за трудностей жизни. Несмотря на все улики, я не верил в сценарий о самоубийстве. ', 'Все что вы читаете - не бред полусонного', 'Начало действия', 0);
INSERT INTO public."CHAPTER" VALUES (7, 5, '- Это кто такой?
- Кто..?
- Ну пасматри на него
- Дэвочка!
- Какая девочка, он же лисый!!
- А.. Дэдушка!!
- Какой дэдушка, не видишь? Белого цвета, кто может быть?
- Кто??
- Тупой что-ли? Уалан де морт это!!
- Пасиба!!
- Пажалста', NULL, 'Исчезнувший стеклопакет', 1);
INSERT INTO public."CHAPTER" VALUES (11, 5, '- Это что такое?
- Это?.. э..
- Это Ларек!!
- А, Ларек!!!!
- А это что такой?
- Эта?..
- Эта марек
- А! Ларек-марек
- Да, ларек-марек
- А это что?
- Это что?
- Это нарек!!
- А! Ларек-марек-нарек!!!
- Да, правильно, ларек-марек-нарек, внимательнее будь', NULL, 'Письма от Ларек', 2);
INSERT INTO public."CHAPTER" VALUES (9, 6, 'ываываываыва', NULL, 'Новая глава 1', 0);


--
-- Data for Name: CHARACTER; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."CHARACTER" VALUES (0, 'Гарри Джеймс Поттер (англ. Harry James Potter) — главный герой Поттерианы, одноклассник и лучший друг Рона Уизли и Гермионы Грейнджер, член Золотого Трио. Самый знаменитый студент Хогвартса за последние сто лет. Первый волшебник, которому удалось противостоять смертельному проклятью «Авада Кедавра», благодаря чему он стал знаменитым и получил прозвище «Мальчик, Который Выжил».', 'Гарри Поттер');
INSERT INTO public."CHARACTER" VALUES (1, 'Гермиона Джин Грейнджер (англ. Hermione Jean Granger) — одна из главных героинь Поттерианы, подруга и однокурсница Гарри Поттера и Рона Уизли, член Золотого Трио. Играет важную роль во всех событиях, которые происходят в жизни Гарри.', 'Гермиона Джин Грейнджер');
INSERT INTO public."CHARACTER" VALUES (2, 'Рональд Билиус «Рон» Уизли (англ. Ronald Bilius «Ron» Weasley) — один из главных персонажей Поттерианы, друг и одноклассник Гарри Поттера и Гермионы Грейнджер, член Золотого Трио. Младший сын в семье Уизли.', 'Рон Уизли');
INSERT INTO public."CHARACTER" VALUES (3, 'Известный Магозоолог и автор "Фантастических зверей и мест их обитания". Родился 24 февраля 1897 года. Еще в раннем детстве он начал интересоваться магическими существами. Он посещал школу чародейства и Волшебства Хогвартс, где его распределили в Пуффендуй. Будучи в Хогвартсе, он был приговорен к исключению, хотя Альбус Дамблдор, который был его преподавателем защиты от темных искусств, признал его невиновность и решительно возражал.', 'Ньют Саламандер');
INSERT INTO public."CHARACTER" VALUES (4, 'Назначенный пилот Евангелиона: Модуля-01, третье дитя. Имеет комплекс неполноценности и тревожно-избегающий тип личности, которые создают определённые проблемы во взаимодействии и установлением контактов с окружающими.', 'Синдзи Икари');
INSERT INTO public."CHARACTER" VALUES (5, 'Капитан, а затем майор в Nerv. Она руководит отделением тактических операций в штаб-квартире Nerv, является ответственной за координацию Евангелионов в реальных боевых действиях. Дочь доктора Кацураги и единственная выжившая из его экспедиции, уничтоженной во время Второго удара. Перенесла сильную психологическую травму в тринадцать лет, что привело к развитию пограничного расстройства личности, из-за которого зрелая Мисато стала неспособной к серьезным продолжительным отношениям.', 'Мисато Кацураги');
INSERT INTO public."CHARACTER" VALUES (6, 'Второе дитя и назначенный пилот Евангелиона: Модуля-02. По сюжету, сформированный к четырнадцати годам комплекс неполноценности и истероидное расстройство личности доставляют девочке разнообразные психические проблемы. Трагичное прошлое, сложный характер и противоречивые отношения с немногочисленными товарищами в военной организации Nerv, в частности, с Синдзи Икари, приводят Аску к состоянию тяжелой депрессии.', 'Аска Лэнгли Сорью');
INSERT INTO public."CHARACTER" VALUES (7, '', 'Следователь');
INSERT INTO public."CHARACTER" VALUES (8, '', 'Профессор');
INSERT INTO public."CHARACTER" VALUES (9, '', 'Голдштерн');


--
-- Data for Name: FANDOM; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."FANDOM" VALUES (1, 'Серия книг (а впоследствии и фильмов) про мальчика, который выжил. Действия книг и фильмов происходят в мире, где кроме обыденной жизни есть волшебники и маги, мистические предметы и невероятные животные', 'Дж. К. Роулинг', 'Гарри Поттер');
INSERT INTO public."FANDOM" VALUES (2, 'Изначально - книга, написанная Дж. К. Роулинг, являющаяся копией книги, принадлежащей Гарри Поттеру, написанной Ньютом Саламандером, известным магическим зоологом. В последствии - серия фильмов, раскрывающая события, предшествующие основному циклу произведений, главным героем которой становится сам Ньют. Несмотря на то, что события происходят в рамках вселенной Гарри Поттера, большинство людей выделяет "Фантастических тварей" в отдельный фандом.', 'Дж. К. Роулинг', 'Фантастические твари и где они обитают');
INSERT INTO public."FANDOM" VALUES (3, 'Сериал рассказывает о 14-ти летнем мальчике Синдзи Икари, который был вызван своим отцом Гэндо Икари для пилотирования био-механического существа, известного как Евангелион, или Ева для краткости. Его задача — сразиться с таинственными существами, известными как Ангелы, которые, как стало известно, должны вскоре начать свои атаки через пятнадцать лет после всемирного катаклизма, известного как «Второй удар». Также сериал раскрывает опыт и эмоции других пилотов Евангелионов и членов организации Nerv, которые пытаются предотвратить ещё одну катастрофу.', 'Хидэаки Анно', 'Евангелион');
INSERT INTO public."FANDOM" VALUES (0, 'Оригинальные работы авторов, оригинальные персонажи, оригинальные (каждый в своей степени) миры и т.д. Простор фантазии ограничен лишь самими авторами.', 'В данном случае каждый является своего рода автором', 'Ориджинал');


--
-- Data for Name: CHARACTER-TO-FANDOM; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (0, 0, 1);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (1, 1, 1);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (2, 2, 1);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (3, 3, 2);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (4, 4, 3);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (5, 5, 3);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (6, 6, 3);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (7, 7, 0);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (8, 8, 0);
INSERT INTO public."CHARACTER-TO-FANDOM" VALUES (9, 9, 0);


--
-- Data for Name: CHARACTER-TO-WORK; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."CHARACTER-TO-WORK" VALUES (0, 7, 1);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (1, 8, 1);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (2, 9, 1);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (4, 4, 4);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (5, 5, 4);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (6, 0, 5);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (7, 1, 5);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (8, 2, 5);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (9, 3, 5);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (10, 4, 6);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (11, 5, 6);
INSERT INTO public."CHARACTER-TO-WORK" VALUES (12, 6, 6);


--
-- Data for Name: COLLECTION; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."COLLECTION" VALUES (0, 0, 'Избранное');
INSERT INTO public."COLLECTION" VALUES (1, 1, 'Избранное');
INSERT INTO public."COLLECTION" VALUES (2, 2, 'Избранное');
INSERT INTO public."COLLECTION" VALUES (3, 3, 'Избранное');
INSERT INTO public."COLLECTION" VALUES (4, 4, 'Избранное');
INSERT INTO public."COLLECTION" VALUES (5, 3, 'Любимое');
INSERT INTO public."COLLECTION" VALUES (6, 5, 'Избранное');


--
-- Data for Name: COLLECTION-TO-WORK; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."COLLECTION-TO-WORK" VALUES (1, 3, 5);
INSERT INTO public."COLLECTION-TO-WORK" VALUES (0, 3, 1);


--
-- Data for Name: COMMENT; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."COMMENT" VALUES (0, 0, 2, 'Для первого раза неплохо. Есть куда расти. Удачи, автор.');
INSERT INTO public."COMMENT" VALUES (1, 0, 1, 'А мне нравится!!');
INSERT INTO public."COMMENT" VALUES (2, 0, 3, 'Прикольно');
INSERT INTO public."COMMENT" VALUES (3, 2, 3, 'тЫ сКаТиЛсЯ!!!!!!!!!!!(((9(((99((((((');
INSERT INTO public."COMMENT" VALUES (4, 5, 3, 'Да, это моя работа))))');
INSERT INTO public."COMMENT" VALUES (5, 5, 3, 'Да, это моя работа))))');
INSERT INTO public."COMMENT" VALUES (6, 2, 4, 'мне нравится. Оч абстрактненько))))');


--
-- PostgreSQL database dump complete
--

