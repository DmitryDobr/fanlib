import React from 'react'

import { useState, useEffect } from "react"
import axios, { isAxiosError } from "axios";

import { useAuth } from '../hoc/useAuth'
import { useOutlet } from 'react-router-dom';


import { WorkEditLink } from '../components/AuthorStudio/WorkEditLink';

import { useNavigate } from 'react-router-dom';


const AuthorStudio = () => {
    let {user} = useAuth();
    const outlet = useOutlet();
    const navigate = useNavigate();

    let [works, setWorks] = useState([]);

    useEffect(() => {loadWorks(user.user_id)}, [user.user_id]);

    // прогрузка работ автора
    async function loadWorks(user_id) {
        axios
        .post(`http://fanlib-api.ru/studio/allworks`, null, {params: {
            'user_id': user_id,
        }})
        .then((response) => {
            setWorks(response.data);
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    // удаление работы автора
    async function deleteWork(event) {
        event.preventDefault();
        const elem = event.target;
        const WorkId = elem.id;

        axios
        .post(`http://fanlib-api.ru/delete/work`, null, {params: {
            'work_id': WorkId,
            'user_id': user.user_id,
        }})
        .then((response) => {
            alert(response.data.message);
            loadWorks(user.user_id);
        })
        .catch((error) => {
            console.log(error.response.data.message);
        });
    }

    // список работ вернет или их отсутствие
    const noOutlet = (works) => {
        return (
            <>
                <button onClick={() => {navigate('./addwork')}} className='LoginForm-button'>Добавить работу</button>
                <hr></hr>
                <h2>Ваши работы:</h2>
                {
                    (works.message !== "No post") 
                    ? (works.map(el => (
                        <WorkEditLink key={el.work_id} WorkId={el.work_id} UserId={user.user_id} deleteFunc={deleteWork}/>
                    ))) 
                    : (<p>Вы еще не создали ни одной работы</p>)
                }
            </>
        )
    }

    return (
        <div className='content'>
            <div className='Author-Works'>
            {
                outlet ||
                (
                    <>
                        <h1>Студия писателя — {user.nickname}</h1>
                        { noOutlet(works) }
                    </>
                )
            }
            </div>
        </div>
    )
}

export {AuthorStudio}