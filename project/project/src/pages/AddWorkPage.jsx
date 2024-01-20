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

const AddWorkPage = () => {
    let {user} = useAuth();
    const Navigate = useNavigate();

    const ref = useRef(null);
    const [isOpen, setOpen] = useState(false);
    const { anchorProps, hoverProps } = useHover(isOpen, setOpen);


    // поиск по названию в фандомах
    var[fandoms, setFandoms] = useState([]);
    async function searchFandoms(event) {
        event.preventDefault();
        const elem = event.target
        const searchString = elem.value
        // console.log(searchString)

        if (searchString.length === 0)
        {
            setFandoms([]);
            return;
        }
        axios
        .post(`http://fanlib-api.ru/fandom/search`, null, { params: {
            name: searchString,
        }})
        .then((response) => {
            setFandoms([]);
            setFandoms(response.data);
            // console.log(response.data);
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }


    var [selectFandoms, selectFandom] = useState([])
     // эффект для актиивации/деактивации выбора персонажа
    useEffect(() => {
        if (selectFandoms.length !== 0)
        {
            document.getElementById('selector-character').disabled = false;
            document.getElementById('selector-fandom').disabled = false;
            document.getElementById('selector-button').disabled = false;
        }
        else
        {
            document.getElementById('selector-character').disabled = true;
            document.getElementById('selector-fandom').disabled = true;
            document.getElementById('selector-button').disabled = true;

            setCChOptions([])
        }
    }, [selectFandoms])

    // пихаем связку id фандома => список персонажей
    const [characterOptions, setChOptions] = useState([]);
    // храним текущую выборку персонажей (для комбобокса)
    const [currentChOptions, setCChOptions] = useState([]);
    // персонажи, которых выбираем в работу
    var [selectedCharacters, setSelectCharacter] = useState([]);

    // выбор фандома => добавить в список выбранных + прогрузить персонажей фандома
    function SelectFandom(el) {
        let flag = true

        for (var i = 0; i < selectFandoms.length; i++)
        {
            if (selectFandoms[i].name === el.name)
            {
                flag = false
            }
        }
        
        if (flag === true)
        {
            axios
            .post(`http://fanlib-api.ru/characters/fromfandom`,null,{params: {
                fandom_id: el.fandom_id,
            }})
            .then((response) => {
                // console.log(response.data);
                setChOptions(characterOptions => [...characterOptions, {id: el.fandom_id, char: response.data}])
            })
            .catch((error) => {
                if (isAxiosError(error))
                {
                    console.log(error.response.data.message);
                }
            });

            selectFandom(selectFandoms => [...selectFandoms, {name: el.name, id: el.fandom_id}])
        }
    }
    
    // развыбор фандома => удалить из списка выбранных + удалить персонажей
    async function DeselectFandom(id) {
        selectFandom(selectFandoms => selectFandoms.filter(item => item.id !== id)) // удаляем из списка выбранных фандомов фандом
        setChOptions(characterOptions => characterOptions.filter(item => item.id !== id)) // удаляем из предлагаемого списка
        // персонажей всех персонажей фандома
        setSelectCharacter(selectedCharacters => selectedCharacters.filter(item => item.id_fandom !== id)) // удаляем из списка
        // выбранных персов всех персов фандома

        // console.log(characterOptions)
        setCChOptions([])
    }

    // выбор списка персонажей из фандома для определения их в combobox с персонажами
    async function setListCharacters(event) {
        event.preventDefault()
        const elem = event.target
        var value = elem.value; // получаем id фандома который выбрали

        let list = characterOptions.find(elem => elem.id === value) // получили из списка доступных персонажей
        // список персонажей на текущий фандом в первом комбобоксе
        setCChOptions(list.char)
    }
    
    // выбрали персонажа => добавляем в список выбранных
    async function SelectCharacter() {
        const elem = document.getElementById('selector-character');
        var value = elem.value;
        const elem1 = document.getElementById('selector-fandom');
        var value_f = elem1.value;
        var text = elem.options[elem.selectedIndex].text;
        console.log(value, text);

        if (selectedCharacters.find(elem => elem.id_char === value) === undefined)
        {
            setSelectCharacter(selectedCharacters => [...selectedCharacters, {name: text, id_char: value, id_fandom: value_f}])
        }
    }
    // развыбор персонажа
    function DeselectCharacter(id) {
        // console.log(id)
        setSelectCharacter(selectedCharacters => selectedCharacters.filter(item => item.id_char !== id))
    }

    // если в вариантах выбора персонажей фандома всего 1 список персонажей => заносим в combobox
    useEffect(() => {
        if (characterOptions.length === 1)
        {
            setCChOptions(characterOptions[0].char)
        }
    }, [characterOptions])


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

        if (WorkName.length === 0 && About.length === 0)
            return 

        axios
        .post(`http://fanlib-api.ru/insert/work`, JSON.stringify(selectedCharacters), {params: {
            user_id: user.user_id ,
            work_name: WorkName,
            original: false,
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
                // console.log(error.response.data.message);
                alert(error.response.data.message)
            }
        });
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

                <div className='filter'>
                    <span>Выберите фандом :</span>
                    <input className='EditForm-text' placeholder='Поиск по фандомам'
                        ref={ref} {...anchorProps}
                        onChange={searchFandoms}
                    ></input>
                </div>

                <div className='selected'>
                    {
                        selectFandoms.map(el => (
                            <div className='Fandom-Select' key={el.id}
                            onClick={() => {DeselectFandom(el.id)}}
                            >{el.name}</div>
                        ))
                    }
                </div>

                <div className='filter' id='filter-character'>
                    <span>Выберите персонажа :</span>
                    <select className='filter-select' id="selector-fandom" onChange={setListCharacters}>
                        {
                            selectFandoms.map(el => (
                                <option key={el.id} value={el.id}
                                >{el.name}</option> 
                            ))
                        }
                    </select>
                    <select className='filter-select' id='selector-character'>
                        {
                            currentChOptions.map(el => (
                                <option key={el.character_id} value={el.character_id}>{el.character_name}</option>
                            ))
                        }
                    </select>
                    <input type='button' className='LoginForm-button' id='selector-button' 
                        onClick={() => {SelectCharacter()}} value={'Добавить'}>
                    </input>
                </div>

                <div className='selected'>
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
        
        <ControlledMenu
            {...hoverProps}
            state={isOpen ? 'open' : 'closed'}
            anchorRef={ref}
            onClick={() => setOpen(false)}
        >
            {fandoms.length > 0 ? 
                (
                    fandoms.map(el => (
                        <MenuItem key={el.fandom_id} id={el.fandom_id}
                        onClick={() => SelectFandom(el)}
                        >{el.name}</MenuItem>
                    )))
                : (<MenuItem>Ничего не найдено</MenuItem>)
            }
        </ControlledMenu>


        </>
    )
}

export {AddWorkPage}