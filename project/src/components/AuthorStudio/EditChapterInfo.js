import React from 'react'
import { useState, useEffect } from "react"

import { useParams } from 'react-router-dom'

import axios, { isAxiosError } from "axios";

import { useAuth } from '../../hoc/useAuth'

import { Notfoundpage } from '../../pages/NotFoundPage';
import { Link } from 'react-router-dom';

const EditChapterInfo = (props) => {
    let {idWork} = useParams();
    let {idChapter} = useParams();
    let {user} = useAuth();

    let [chapterObject, setChapter] = useState([]);
    useEffect(() => {loadChapter(props.WorkId)}, [props])
    
    async function loadChapter() {
        axios
        .post(`http://fanlib-api.ru/studio/chapter`, null, {params: {
            'work_id': idWork,
            'user_id': user.user_id,
            'chapter_id': idChapter
        }})
        .then((response) => {
            // console.log(response.data);

            if (response.data.length > 0)
            {
                setChapter(response.data[0]);
                setActivityChanged(true);
            }
        })
        .catch((error) => {
            setChapter(error.response.data);
            console.log(error.response.data.message);
        });
    }

    const updateChapter = (event) => {
        event.preventDefault()
        const form = event.target;

        const newChapterName = form.chapter_name.value;
        const newChapterText = form.chapter_text.value;
        const newChapterNumber = form.chapter_number.value;

        axios
        .post(`http://fanlib-api.ru/update/chapter`, [{chapter_text: newChapterText,}], {params: {
            user_id: user.user_id ,
            work_id: idWork,
            chapter_id: idChapter,
            chapter_name: newChapterName,
            chapter_number: newChapterNumber
        }
        })
        .then((response) => {
            // console.log(response.data);
            alert(response.data.message)
            loadChapter();
            setActivityChanged(true)
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }


    // проверка на изменения изначальных состояний
    let changes = {
        "chapter_name": 0,
        "chapter_text": 0,
        "chaprer_number": 0,
    } // слежка за изменениями
    const [buttonDisable, setActivityChanged] = useState(true); // переключатель доступности кнопки
    const ElemUpdate = (event) => {
        event.preventDefault()
        const elem = event.target;


        if (chapterObject[elem.name] !== elem.value) {
            changes[elem.name] = 1
        } else {
            changes[elem.name] = 0
        }

        setActivityChanged(true)

        for (var key in changes) {
            if (changes[key] === 1)
                setActivityChanged(false)
        }
    } // проверка на изменения изначальных состояний

    if (chapterObject !== undefined)
    {
        if (chapterObject.status !== false)
        {
            return (
                <div className='content'>
                    <Link to='../'>В писательскую студию</Link>
                    <h1>Редактирование главы  - "{chapterObject.chapter_name}"</h1>

                    <form className='EditForm' onSubmit={updateChapter}>

                        <div className='EditForm-container'>
                            <label className='EditForm-label'>Название главы:</label>
                            <input onChange={ElemUpdate} 
                                className='EditForm-text' 
                                name='chapter_name' defaultValue={chapterObject.chapter_name}>
                            </input>
                        </div>

                        <div className='EditForm-container'>
                            <label className='EditForm-label'>Номер главы:</label>
                            <input type='number' onChange={ElemUpdate} 
                                className='EditForm-text' 
                                name='chapter_number' defaultValue={chapterObject.chapter_number}>
                            </input>
                        </div>
                        
                        <label className='EditForm-label'>Текст главы :</label>
                        <textarea onChange={ElemUpdate} className='EditForm-textarea' 
                            maxlength='1000' name="chapter_text" id="0" cols="30" rows="10" 
                            defaultValue={chapterObject.chapter_text}>
                        </textarea>

                        <input disabled={buttonDisable} className='LoginForm-button' type="submit" value='Сохранить' />

                    </form>
                </div>
            )
        }
        else {
            return <Notfoundpage />
        }
    }
}

export {EditChapterInfo}