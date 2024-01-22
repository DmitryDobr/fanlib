import React from 'react'
import { useState, useEffect } from "react"

import { useParams } from 'react-router-dom'

import axios, { isAxiosError } from "axios";

import { useAuth } from '../../hoc/useAuth'

import { Notfoundpage } from '../../pages/NotFoundPage';
import { Link } from 'react-router-dom';

const EditWorkInfo = () => {

    let {idWork} = useParams();
    let [workObject, setWork] = useState([]);
    
    let {user} = useAuth();


    // слежка за изменениями
    let changes = {
        "WorkName": 0,
        "about": 0,
        "remark": 0, 
    }

    // переключатель доступности кнопки
    const [buttonDisable, setActivityChanged] = useState(true); 

    useEffect(() => {
        axios
        .post(`http://fanlib-api.ru/studio/work`, null, {params: {
            'work_id': idWork,
            'user_id': user.user_id
        }})
        .then((response) => {
            // console.log(response.data);

            if (response.data.length > 0)
            {
                setWork(response.data[0]);
                setActivityChanged(true)
            }
        })
        .catch((error) => {
            setWork(error.response.data);
            console.log(error.response.data.message);
        });
    }, [idWork, user.user_id]);


    // запрос на обновление работы
    const updateWork = (event) => {
        event.preventDefault()
        const form = event.target;

        const newWorkName = form.WorkName.value;
        const newRemark = form.remark.value;
        const newAbout = form.about.value;

        var bodyFormData = new FormData();
        bodyFormData.append('user_id', user.user_id);
        bodyFormData.append('work_id', idWork);
        bodyFormData.append('about', newAbout);
        bodyFormData.append('name', newWorkName);
        bodyFormData.append('remark', newRemark);

        axios
        .post(`http://fanlib-api.ru/update/work`, bodyFormData, {params: {}})
        .then((response) => {
            // console.log(response.data)
            alert(response.data.message)
            // user.nickname = newUserName
            workObject.WorkName = newWorkName
            workObject.about = newAbout
            workObject.remark = newRemark
            setActivityChanged(true)
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }

    // проверка на изменения изначальных состояний
    const ElemUpdate = (event) => {
        event.preventDefault()
        const elem = event.target;


        if (workObject[elem.name] !== elem.value) {
            changes[elem.name] = 1
        } else {
            changes[elem.name] = 0
        }

        setActivityChanged(true)

        for (var key in changes) {
            if (changes[key] === 1)
                setActivityChanged(false)
        }
    }


    if (workObject !== undefined)
    {
        if (workObject.status !== false)
        {
            return (
                <div className='content'>
                    <Link to='../'>В писательскую студию</Link>

                    <h1>Основная информация — "{workObject.WorkName}"</h1>
    
                    <form className='EditForm' onSubmit={updateWork}>
                        <div className='EditForm-container'>
                            <label className='EditForm-label'>Название:</label>
                            <input onChange={ElemUpdate} 
                                className='EditForm-text' 
                                name='WorkName' defaultValue={workObject.WorkName}>
                            </input>
                        </div>
                        
                        <label className='EditForm-label'>Краткое описание/отрывок из работы :</label>
                        <textarea onChange={ElemUpdate} className='EditForm-textarea' 
                            maxlength='1000' name="about" id="0" cols="30" rows="10" 
                            defaultValue={workObject.about}>
                        </textarea>
                        
                        <label className='EditForm-label'>Авторские пометки о работе :</label>
                        <textarea onChange={ElemUpdate} className='EditForm-textarea' 
                            maxlength='1000' name="remark" id="0" cols="30" rows="10" 
                            defaultValue={workObject.remark}>
                        </textarea>
                        
                        <input disabled={buttonDisable} className='LoginForm-button' type="submit" value='Сохранить' />
                    </form>
                </div>
            )
        }
        else {
            return (<Notfoundpage />)
        }
    }
}

export {EditWorkInfo}