import React from 'react'

import { Link, useParams } from 'react-router-dom'
import { useState, useEffect } from "react"
import axios, { isAxiosError }  from 'axios'

// import { useNavigate } from 'react-router-dom'


const ReadChapter = () => {

    // const navigate = useNavigate();

    let {idWork} = useParams()
    let {idChapter} = useParams()

    let [chapterObject, setChapter] = useState([]);

    useEffect(() => {
        axios
        .get(`http://fanlib-api.ru/select/chapter?WorkId=${idWork}&chapterId=${idChapter}`)
        .then((response) => {
            setChapter(response.data[0])
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                setChapter(error.response.data)
                console.log(error.response.data.message);
            }
        });
    }, [idWork, idChapter]);



    if (chapterObject !== undefined) {
        if (chapterObject.status !== false) {
            return (
                <>
                    <Link to='../'>Назад к оглавлению</Link>
                    {/* <button onClick={() => {navigate(`../`)}}>Оглавление</button> */}
                    <div className='ReadWork-BigText'>{chapterObject.chapter_name}</div>
                    <div className='ReadWork-SmallText'>
                        {chapterObject.chapter_text}
                    </div>
                </>
            )
        }
        else {
            return(
                <>
                    <div>Упс!! Глава не найдена</div>
                    <Link to='../'>Назад к оглавлению</Link>
                </>
            )
        }
    }
}

export {ReadChapter}
