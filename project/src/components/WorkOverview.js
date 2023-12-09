import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import axios, { isAxiosError } from "axios";


/*
    Компонент, отвечающий за отрисовку карточки работы
    Работа имеет след.информацию: 
      название фанфика
      пользователь, который ее создал
      краткое описание работы
      время апдейта последнее
      статус работы
        список персонажей работы
          список фандомов работы
*/


const WorkOverview = (props) => {

    let [workParams, setWorkParams] = useState([]);

    useEffect(() => {
        axios
        .get(`http://fanlib-api.ru/works/one/${props.WorkId}`)
        .then((response) => {
        setWorkParams(response.data[0]);
        })
        .catch((error) => {
        if (isAxiosError(error))
        {
            console.log(error.response.data.message);
        }
        });
    }, [props]);

    const CharactersList = (CharactersList) => {
        if (CharactersList === undefined)
            return

        // console.log(CharactersList)  

        if (CharactersList.length > 0)
        {
            return (
                <>
                    <span> Персонажи: </span>
                    {CharactersList.map(el => (
                        <span className='Overview-Character' key={el.character_id}>{el.character_name}</span>
                    ))}
                </>
            )
        }
        else
        {
            return(<span>Персонажи не определены</span>)
        }
    }

    const FandomList = (FandomsList) => {
        if (FandomsList === undefined)
            return

        if (FandomsList.length > 0)
        {
            return (
                <>
                    <span> Фандом: </span>
                    {FandomsList.map(el => (
                        <>
                        <Link key={el.fandom_id} to={`/fandom/${el.fandom_id}`}>{el.name}</Link>
                        <span> </span>
                        </>
                    ))}
                </>
            )
        }
        else
        {
            return(<span>Фандом не определен</span>)
        }
    }


    return (
        <div className='Overview'>
            <Link className='Overview-title' to = {`/read/${workParams.work_id}`}>{workParams.WorkName}</Link>

            <div>
                <span>Автор: </span>
                <Link className='CreatorLink' to = {`/author/${workParams.user_id}`}>{workParams.nickname}</Link>
            </div>

            <div>
                <span> Дата обновления: </span>
                <span className='Overview-status'>{workParams.update_time}</span>
            </div>

            <div>
                <span> Статус: </span>
                <span className='Overview-status'>{(Number(workParams.WORK_STATUS) === 2) ? ("Завершено"):("В работе")}</span>
            </div>
            <hr></hr>
            <div>
                {FandomList(workParams.fandom)}
            </div>
            <div>
                {CharactersList(workParams.characters)}
            </div>

            <div className='Overview-about'>
                <span className='Overview-bigspan'> Краткое описание: </span>
                <div className='Overview-abouttext'>{workParams.about}</div>
            </div>
        </div>
    )
}

export {WorkOverview}