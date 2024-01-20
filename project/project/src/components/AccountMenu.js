import { useRef, useState } from 'react';
import { ControlledMenu, MenuDivider, MenuItem, useHover } from '@szhsin/react-menu';
import '@szhsin/react-menu/dist/index.css';
import '@szhsin/react-menu/dist/transitions/slide.css';

import { useNavigate } from 'react-router-dom';

const HoverMenu = (props) => {

    const navigate = useNavigate();

    const ref = useRef(null);
    const [isOpen, setOpen] = useState(false);
    const { anchorProps, hoverProps } = useHover(isOpen, setOpen);

    return (
        <>
            <span className='Account-Menu' ref={ref} {...anchorProps}>
                {props.nickname}
            </span>

            <ControlledMenu
                {...hoverProps}
                state={isOpen ? 'open' : 'closed'}
                anchorRef={ref}
                onClose={() => setOpen(false)}
            >
                <MenuItem onClick={() => {navigate(`./author/${props.user_id}`)}}>Моя страница</MenuItem>
                <MenuItem onClick={() => {navigate(`./mycollections`)}}>Мои коллекции работ</MenuItem>
                <MenuDivider></MenuDivider>
                <MenuItem onClick={() => {navigate(`./editprofile`)}}>Редактировать профиль</MenuItem>
                <MenuItem onClick={() => {navigate(`./studio`)}}>Писательская студия</MenuItem>
                <MenuDivider></MenuDivider>
                <MenuItem onClick={() => props.logout()}>Выход</MenuItem>
            </ControlledMenu>
        </>
    );
}

export {HoverMenu}