import React from 'react'

import axios, {isAxiosError} from 'axios'
import { useState, useEffect } from "react"
import { useRef } from 'react';

import { useAuth } from '../hoc/useAuth'

import { useParams } from 'react-router-dom';

import { ControlledMenu, MenuDivider, MenuItem, useHover, useMenuState } from '@szhsin/react-menu';

import '@szhsin/react-menu/dist/index.css';
import '@szhsin/react-menu/dist/transitions/slide.css';

import { useNavigate } from 'react-router-dom';

import { FormFandom } from '../components/AddWork/FormFandom';
import { FormOriginal } from '../components/AddWork/FormOriginal';

const AddWorkPage = () => {
    let {user} = useAuth();
    const Navigate = useNavigate();


    // персонажи, которых выбираем в работу
    var [selectedCharacters, setSelectCharacter] = useState([]);
    var [workType, setType] = useState(0); // тип работы: 0 - не выбран, 1 - по фандому, 2 - ориджинал
    

    // выбрали персонажа => добавляем в список выбранных
    async function SelectCharacter() {
        const elem = document.getElementById('selector-character');
        var value = elem.value; // id персонажа
        const elem1 = document.getElementById('selector-fandom');
        var value_f = elem1.value; // определение id фандома по первому комбобоксу
        var text = elem.options[elem.selectedIndex].text; // имя персонажа
        console.log(value, text);

        if (selectedCharacters.find(elem => elem.id_char === value) === undefined)
        {
            setSelectCharacter(selectedCharacters => [...selectedCharacters, {name: text, id_char: value, id_fandom: value_f}])
        }
    }


    async function InsertCharacter(name, id_character, id_fandom) {

        if (selectedCharacters.find(elem => elem.id_char === id_character) !== undefined)
            return;
        if (selectedCharacters.find(elem => elem.name === name) !== undefined)
            return;


        setSelectCharacter(selectedCharacters => [...selectedCharacters, {name: name, id_char: id_character, id_fandom: id_fandom}])
        
    }

    // развыбор персонажа
    function DeselectCharacter(id) {
        // console.log(id)
        setSelectCharacter(selectedCharacters => selectedCharacters.filter(item => item.id_char !== id))
    }


    // финальное событие на добавление работы
    async function AddNewWork(event) {
        event.preventDefault()
        const form = event.target

        // console.log(selectedCharacters)
        // console.log(selectFandoms)

        // console.log(form.WorkName.value)
        // console.log(form.about.value)

        const WorkName = form.WorkName.value
        const About = form.about.value

        if (WorkName.length === 0 || About.length === 0) {
            alert('Добавьте название и описание работы')
            return 
        }

        if (selectedCharacters === undefined || selectedCharacters.length === 0) {
            alert('Добавьте персонажей в работу')
            return
        }

        console.log(selectedCharacters)

        axios
        .post(`http://fanlib-api.ru/insert/work`, JSON.stringify(selectedCharacters), {params: {
            user_id: user.user_id ,
            work_name: WorkName,
            original: workType,
            about: About,
        }
        })
        .then((response) => {
            // console.log(response.data);
            alert(response.data.message)

            if (response.data.status === true)
            {
                Navigate(`../../author/${user.user_id}`);
            }
        })
        .catch((error) => {
            console.log(error)
            if (isAxiosError)
            {
                console.log(error.response.data);
                alert(error.response.data.message)
            }
        });
    }

    async function ChangeType(t) {
        setType(t);
        setSelectCharacter([]);
    }



    return (
        <>
        <div className='content'>
            <h1>Добавить новую работу</h1>

            <form className='EditForm' onSubmit={AddNewWork}>
                <input className='EditForm-text' name="WorkName" placeholder='Название работы'></input>
                
                <textarea className='EditForm-textarea' 
                    maxLength='1000' name="about" id="0" cols="30" rows="10" 
                    placeholder='Краткое описание/отрывок из текста'>
                </textarea>

                <div>
                    <input name="fandom_exist" type="radio" value={1} onClick={() => {ChangeType(1)}}/> Работа по существующему фандому
                    <input name="fandom_exist" type="radio" value={2} onClick={() => {ChangeType(2)}}/> Ориджинал
                </div>

                {
                    (workType === 1) && (
                        <FormFandom SelectCharacter = {SelectCharacter} setSelectCharacter = {setSelectCharacter}/>
                    )
                }
                {
                    (workType === 2) && (
                        <FormOriginal InsertCharacter = {InsertCharacter}/>
                    )
                }

                <div className='selected'>
                    <>Выбранные персонажи:</>
                    {
                        selectedCharacters.map(el => (
                            <div className='Fandom-Select' key={el.id_char}
                            onClick={() => {DeselectCharacter(el.id_char)}}
                            >{el.name}</div>
                        ))
                    }
                </div>

                <input className='LoginForm-button' type='submit' value={'Подтвердить'}></input>
            </form>
        </div>
        </>
    )
}

export {AddWorkPage}