import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import axios, { isAxiosError } from "axios";


// редактирование первичной информации работы в студии
// удаление/добавление глав, переход на редактирование информации о работе/главах

const WorkEditLink = (props) => {

    let [workParams, setWorkParams] = useState([]);
    let [chapters, setChapters] = useState([]);

    useEffect(() => {loadWork()}, [props])
    useEffect(() => {loadChapters(props.WorkId)}, [props])
   
    // добавление главы в работу
    async function AddWChapter(WorkId, UserId) {

        axios
        .post(`http://fanlib-api.ru/insert/chapter`, null, {params: {
            'user_id': UserId,
            'work_id': WorkId,
        }})
        .then((response) => {
            console.log(response.data.message);

            if (response.data.status === true)
            {
                loadChapters(WorkId)
            }
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }

    // прогрузка инфы о работе
    async function loadWork() {
        axios
        .post(`http://fanlib-api.ru/studio/work`, null, {params: {
            'work_id': props.WorkId,
            'user_id': props.UserId
        }})
        .then((response) => {
            setWorkParams(response.data[0]);
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }

    // загрузка глав работы
    async function loadChapters(WorkId) {
        axios
        .post(`http://fanlib-api.ru/select/workchapters`, null, {params: {
            'WorkID': WorkId
        }})
        .then((response) => {
            // console.log(response.data)
            setChapters(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    // удаление главы из работы
    async function deleteChapter(event) {
        event.preventDefault()
        const elem = event.target;
        const chapter_id = elem.id;

        axios
        .post(`http://fanlib-api.ru/delete/chapter`, null, {params: {
            'work_id': props.WorkId,
            'user_id': props.UserId,
            'chapter_id': chapter_id,
        }})
        .then((response) => {
            alert(response.data.message);
            loadChapters(props.WorkId);
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }

    return (
        <div className='work-studio'>
            <div className='work-row1'>
                <div className='work-column'>{workParams.WorkName}</div>

                <div className='work-column work-actions'>
                    <Link className='work-action' to={`/read/${props.WorkId}`}>Страница работы</Link>

                    <Link className='work-action' to={`./editwork/${props.WorkId}`}>Редактировать</Link>
                
                    <span className='deletebtn' id={props.WorkId} onClick={props.deleteFunc}>Удалить</span>
                </div>
            </div>

            <div className='work-row2'>
                <div className='work-column'>
                    <span> Дата обновления: </span>
                    <span className=''>{workParams.update_time}</span>
                </div>

                <div className='work-column'>
                    <span> Статус: </span>
                    <span className=''>{(Number(workParams.WORK_STATUS) === 2) ? ("Завершено"):("В работе")}</span>
                </div>
            </div>

            <hr></hr>

            <div className='Chapters-studio'>
                
                <div className='Chapters-studio-list'>
                    {
                        chapters.length > 0 && (
                            chapters.map(el => (
                                <div className='Studio-Chapter' key = {el.chapter_id}>
                                    <span>{el.chapter_name}</span>
                                    <span className='work-actions'>
                                        <Link to={`editwork/${props.WorkId}/chapter/${el.chapter_id}`}>Редактировать</Link>
                                        <span className='deletebtn' id={el.chapter_id} onClick={deleteChapter}>Удалить</span>
                                    </span>
                                </div>
                            ))
                        )
                    }
                </div>

                <button className='AddChapter-button' onClick={() => {AddWChapter(props.WorkId, props.UserId)}}>Добавить главу</button>
            </div>
            
        </div>
    )
}

export {WorkEditLink}