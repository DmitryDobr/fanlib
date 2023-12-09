import { Routes, Route } from 'react-router-dom';
// основной каркас сайта
import Layout from './components/layout';
// основной каркас сайта

// домашняя и ненайденная страницы
import { Homepage } from './pages/Homepage';
import { Notfoundpage } from './pages/NotFoundPage';
// домашняя и ненайденная страницы

import { WorksPage } from './pages/WorksPage';

import { AutorsPage } from './pages/AuthorsPage';
import { AutorPage } from './pages/AuthorPage';

// страницы касательно чтения работ
import { ReadFikPage } from './pages/ReadFik';
import { ReadChapter } from './components/ReadWork/ReadChapter';
// страницы касательно чтения работ

// страницы касательно инфы про фандомы
import {FandomSearch} from './pages/FandomSearch';
import {FandomPage} from './pages/FandomPage';
import { Characters } from './components/Characters';
// страницы касательно инфы про фандомы

// страница входа
import { LoginPage } from './pages/LoginPage';
// страница входа

// страницы связанные с аккаунтом (редактирование профиля, студия и т.д.)
import { EditProfile } from './pages/EditProfile';
import { AuthorStudio } from './pages/AuthorStudio';
import { EditWorkInfo } from './components/AuthorStudio/EditWorkInfo';
import { EditChapterInfo } from './components/AuthorStudio/EditChapterInfo';
import { AddWorkPage } from './pages/AddWorkPage';
import { UserCollectionsPage } from './pages/UserCollectionsPage';
import { Collection } from './components/AuthorStudio/Collection';
// страницы связанные с аккаунтом



// провайдер авторизации
import { RequireAuth } from './hoc/RequireAuth';
import { AuthProvider } from './hoc/AuthProvider';
// провайдер авторизации


import './style.css';

function App() {
  return (
    <AuthProvider>
        <Routes>
            <Route path="/" element={<Layout />} >

                <Route index element={<Homepage />} />

                {/* Страницы с списками/поиском работ */}
                <Route path="works/new" element={<WorksPage />} />

                {/* страницы отображения новых, популярных и рандомных авторов */}
                <Route path="authors/new" element={<AutorsPage type="new"/>} />
                <Route path="authors/popular" element={<AutorsPage type="popular"/>} />
                <Route path="authors/random" element={<AutorsPage type="random"/>} />

                
                <Route path="author/:idAuthor" element={<AutorPage />} > {/* страница конкретного автора */}
                    
                </Route>

                
                <Route path="fandoms/search" element={<FandomSearch />} /> {/* Страница поиска фандомов */}

                {/* страница вывода инфы о фандоме */}
                <Route path="fandom/:idFandom" element={<FandomPage />} >
                    <Route path="characters" element={<Characters />} />
                </Route>

                {/* страница чтения работы */}
                <Route path="read/:idWork" element={<ReadFikPage />} >
                    {/* Вложенная страница с секцией комментариев */}
                    <Route path="comments" element={<>Comments</>}></Route>
                    {/* Вложенная страница чтения главы */}
                    <Route path='part/:idChapter' element={<ReadChapter />}/>
                </Route>

                
                <Route path="login" element={<LoginPage />} /> {/* Роут на страницу логина */}

                
                <Route path="editprofile" element={
                    <RequireAuth>
                        <EditProfile />
                    </RequireAuth>
                } /> {/* редактирование профиля */}

                
                {/* авторская студия */}
                <Route path="studio" element={
                    <RequireAuth>
                        <AuthorStudio />
                    </RequireAuth>
                }>
                    {/* редактирование работы */}
                    <Route path='editwork/:idWork' element={<EditWorkInfo />}/>
                    <Route path='editwork/:idWork/chapter/:idChapter' element={<EditChapterInfo/>} />
                    <Route path='addwork' element={<AddWorkPage/>}/>
                </Route>

                {/* пользовательские коллекции работ */}
                <Route path="mycollections" element={
                    <RequireAuth>
                        <UserCollectionsPage />
                    </RequireAuth>
                }>
                    <Route path=':idCollection' element={<Collection />}/>
                </Route>

                {/* л.другие страницы */}
                <Route path="*" element={<Notfoundpage />} />
                
            </Route>
        </Routes>
    </AuthProvider>
  );
}

export default App;
