import React from 'react'

import { useState, useEffect } from "react"
import { useParams } from 'react-router-dom';
import axios, { isAxiosError } from "axios";
import { Link } from 'react-router-dom'
import { useOutlet } from 'react-router-dom';


import { Notfoundpage } from '../pages/NotFoundPage'

import { AddComment } from '../hoc/AddComment';
import { InsertWorkCollection } from '../hoc/insertWorkCollection';

import ChaptersMenu from '../components/ReadWork/ChaptersMenu';

// Страница, отображающая конкретную работу. В начале отображается список глав. КОгда глава выбрана, Outlet отобразит саму главу

const ReadFikPage = () => {

    const outlet = useOutlet();

    let {idWork} = useParams();

    let [workObject, setWork] = useState([]);
    let [chapterObject, setChapters] = useState([]);
    let [commentObject, setComments] = useState([]);

    // запрос на получение инфы с сервера о работе
    useEffect(() => {
        axios
        .get(`http://fanlib-api.ru/select/work?work_id=${idWork}`)
        .then((response) => {
            if (response.data.length > 0)
            {
                setWork(response.data[0]);
                setChapters(response.data[0].Chapters)
            }
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                setWork(error.response.data);
                console.log(error.response.data.message);
            }
        });
    }, [idWork]);

    useEffect(() => {loadComments(idWork)}, [idWork])

    // console.log(chapterObject)

    // Загрузка комментов
    async function loadComments(WorkId) {
        axios
        .get(`http://fanlib-api.ru/select/comments?WorkID=${WorkId}`)
        .then((response) => {
            // console.log(response.data)
            setComments(response.data);
        })
        .catch((error) => {
        if (isAxiosError(error))
        {
            console.log(error.response.data.message);
        }
        });
    }
    
    if (workObject !== undefined)
    {
        if (workObject.status !== false)
        {
            return (
                <div className='content'>
                    <div className='ReadWork'>
                        <div className='ReadWork-Fragment1'>
                            <div className='ReadWork-Title'>
                                <span className='ReadWork-WorkName'>{workObject.WorkName}</span>
                                <InsertWorkCollection WorkId={idWork} />
                            </div> 
                            <div>
                                <span>Автор: </span><Link to={`/author/${workObject.user_id}`}>{workObject.nickname}</Link>
                            </div>
                            <div>
                                <span>Время обновления: </span><span>{workObject.update_time}</span>
                            </div>
                            <div>
                                <span>Статус: </span>{(Number(workObject.WORK_STATUS) === 2) ? ("Завершено"):("В работе")}
                            </div>
                            <div>
                                <span>Описание: </span><span>{workObject.about}</span>
                            </div>
                        </div>

                        <div className='ReadWork-Fragment2'>
                            {
                                outlet ||
                                (chapterObject !== undefined && (
                                    <ChaptersMenu Chapters={chapterObject} idWork={idWork}/>
                                ))
                            }
                        </div>
                        
                        <div className='ReadWork-CommentHeader'>
                            <span>Комментарии</span>
                            <button className='LoginForm-button' onClick={(event) => {
                                let elem = document.getElementById(0);
                                let state = elem.style.display; //смотрим, включен ли сейчас элемент
                                if (state ==='') elem.style.display='none'; //если включен, то выключаем
                                else elem.style.display=''; //иначе - включаем
                            }}>Показать/Скрыть</button>
                        </div>

                        <div className='ReadWork-Fragment3' style={{'display': 'none'}} id='0'>
                            {
                                // !outlet && 
                                (
                                    commentObject.length > 0 ?
                                    commentObject.map(el => (
                                    <div className='comment' key={el.comment_id}>
                                        <p>{el.text}</p>
                                        <Link className='Copyright' to = {`/author/${el.id_user}`}>{el.nickname}</Link>
                                    </div>
                                    )) : <>Без комментариев</>
                                )
                            }
                            <AddComment callback={loadComments} WorkId={idWork}/>
                        </div>
                    </div>
                </div>
            )
        }
        else{
            return(<Notfoundpage />)
        }
    }
}

export {ReadFikPage}