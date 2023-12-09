import React from 'react'

import { useState, useEffect } from "react"
import axios, { isAxiosError } from "axios";

import { useAuth } from '../hoc/useAuth'

const EditProfile = () => {

    let {user} = useAuth();
    let [author, setAuthor] = useState([]);
    
    useEffect(() => {
        axios
        .get(`http://fanlib-api.ru/user/userinfo?user_id=${user.user_id}`)
        .then((response) => {
            // console.log(response.data);
            setAuthor(response.data);
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }, [user.user_id]);

    const updateProfile = (event) => {
        event.preventDefault()
        const form = event.target;

        const newUserName = form.username.value;
        const newDate = form.date.value;
        const newAbout = form.about.value;

        // console.log(newDate);

        axios
        .post(`http://fanlib-api.ru/user/updateuserinfo`, null, {params: {
            user_id: user.user_id,
            about: newAbout,
            birth: newDate,
            nickname: newUserName,
        }})
        .then((response) => {
            console.log(response.data)
            alert(response.data.message)
            user.nickname = newUserName
        })
    }

    return (
        <div className='content'>
            <h1>Редактировать профиль — {user.nickname}</h1>

            <form className='EditForm' onSubmit={updateProfile}>
                <div className='EditForm-container'>
                    <label className='EditForm-label'>Имя пользователя</label>
                    <input className='EditForm-text' name='username' type="text" placeholder='никнейм' defaultValue={author.nickname} />
                </div>
                <div className='EditForm-container'>
                    <label className='EditForm-label'>День рождения</label>
                    <input className='EditForm-date' id="dateRequired" name='date' type="date" defaultValue={author.birth} />
                </div>
                    <label className='EditForm-label'>Расскажите о себе:</label>
                    <textarea className='EditForm-textarea' maxlength='1000' name="about" id="0" cols="30" rows="10" defaultValue={author.about}></textarea>
                <input className='LoginForm-button' type="submit" value='Сохранить' />
            </form>
        </div>
    )
}

export {EditProfile}